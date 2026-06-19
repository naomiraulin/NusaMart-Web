<?php

namespace App\Http\Controllers\Web\Buyer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\CheckoutRequest;
use App\Models\CartItem;
use App\Models\CourierOption;
use App\Models\PaymentMethod;
use App\Models\UserAddress;
use App\Services\CartService;
use App\Services\NotificationService;
use App\Services\OrderService;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    private const COURIER_PRICES = [
        'CRR-REG' => 9000,
        'CRR-EXP' => 15000,
        'CRR-KRG' => 20000,
        'CRR-SIK' => 8000,
        'CRR-SIH' => 18000,
        'CRR-JTR' => 9000,
        'CRR-COD' => 10000,
    ];

    public function __construct(
        private OrderService        $orderService,
        private CartService         $cartService,
        private PaymentService      $paymentService,
        private NotificationService $notificationService,
    ) {}

    /**
     * Riwayat semua pesanan buyer.
     */
    public function index(): View
    {
        /** @var \App\Models\User $user */
        $user   = Auth::user();
        $orders = $this->orderService->getByUser($user->idUser);

        return view('buyer.orders.index', compact('orders'));
    }

    /**
     * Detail satu pesanan.
     */
    public function show(string $id): View
    {
        $order = $this->orderService->getById($id);

        return view('buyer.orders.show', compact('order'));
    }

    /**
     * Halaman checkout.
     * Menerima cart_item_ids[] via GET dari halaman cart.
     * Item yang dipilih dikelompokkan per toko untuk ditampilkan di checkout.
     */
    public function checkout(Request $request): View|RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $cartItemIds = $request->input('cart_item_ids', []);

        if (empty($cartItemIds)) {
            return redirect()->route('buyer.cart.index')
                ->with('error', 'Pilih minimal satu produk untuk checkout.');
        }

        // Ambil CartItem yang dipilih, pastikan milik user ini
        $cart          = $this->cartService->getCart($user->idUser);
        $selectedItems = $cart->cartItems
            ->whereIn('idCartItem', $cartItemIds)
            ->values();

        if ($selectedItems->isEmpty()) {
            return redirect()->route('buyer.cart.index')
                ->with('error', 'Item yang dipilih tidak ditemukan.');
        }

        $addresses = UserAddress::where('idUser', $user->idUser)->get();
        $methods   = PaymentMethod::where('isActive', true)->get();

        // Inject harga flat rate ke setiap CourierOption tanpa mengubah model/DB
        $courierPrices = self::COURIER_PRICES;
        $couriers = CourierOption::where('isActive', true)
            ->get()
            ->map(function ($courier) use ($courierPrices) {
                $courier->price = $courierPrices[$courier->idCourier] ?? 0;
                return $courier;
            });

        return view('buyer.orders.checkout', compact(
            'selectedItems',
            'cartItemIds',
            'addresses',
            'couriers',
            'methods',
        ));
    }

    /**
     * Halaman Checkout Khusus "Beli Langsung".
     */
    public function directCheckout(Request $request): View|RedirectResponse
    {
        $user = Auth::user();
        $itemId = $request->input('item_id');
        $quantity = (int) $request->input('quantity', 1);

        if (!$itemId) {
            return back()->with('error', 'Pilih variasi produk terlebih dahulu.');
        }

        $productItem = \App\Models\ProductItem::with(['product.store', 'product.productImages', 'productVariations'])->findOrFail($itemId);

        // Membuat CartItem "Mock/Palsu" agar Blade checkout tidak error
        $mockCartItem = new \App\Models\CartItem([
            'idCartItem' => 'DIRECT',
            'idItem' => $itemId,
            'quantity' => $quantity,
        ]);
        $mockCartItem->setRelation('productItem', $productItem);

        $selectedItems = collect([$mockCartItem]);
        $cartItemIds = ['DIRECT'];
        
        // Flag penanda untuk di Blade
        $isDirect = true; 
        $directItemId = $itemId;
        $directQty = $quantity;

        $addresses = UserAddress::where('idUser', $user->idUser)->get();
        $methods   = PaymentMethod::where('isActive', true)->get();
        
        $courierPrices = self::COURIER_PRICES;
        $couriers = CourierOption::where('isActive', true)->get()->map(function ($courier) use ($courierPrices) {
            $courier->price = $courierPrices[$courier->idCourier] ?? 0;
            return $courier;
        });

        return view('buyer.orders.checkout', compact(
            'selectedItems', 'cartItemIds', 'addresses', 'couriers', 'methods', 'isDirect', 'directItemId', 'directQty'
        ));
    }

    /**
     * Proses pembuatan Order dari "Beli Langsung".
     */
    public function placeOrderDirect(Request $request): RedirectResponse
    {
        $user = Auth::user();
        
        // Validasi Manual
        $validated = $request->validate([
            'id_address' => 'required',
            'id_method'  => 'required',
            'stores'     => 'required|array',
            'direct_item_id' => 'required',
            'direct_qty'     => 'required|numeric|min:1',
            'service_price'  => 'nullable|numeric'
        ]);

        $itemId = $validated['direct_item_id'];
        $quantity = (int) $validated['direct_qty'];
        $storesData = $validated['stores'];
        $servicePrice = (float) ($validated['service_price'] ?? 0);
        $storeData = reset($storesData); // Ambil data toko pertama (karena beli langsung pasti 1 toko)

        if (!isset($storeData['shipping_cost']) || $storeData['shipping_cost'] === '') {
            $storeData['shipping_cost'] = self::COURIER_PRICES[$storeData['id_courier']] ?? 0;
        }

        $result = $this->orderService->checkoutDirect(
            userId: $user->idUser,
            itemId: $itemId,
            quantity: $quantity,
            storeData: $storeData,
            idAddress: $validated['id_address'],
            servicePrice: $servicePrice,
        );

        $orders = $result['orders'];
        $totalAmount = $result['grand_total'];

        $payment = $this->paymentService->create(
            userId: $user->idUser,
            methodId: $validated['id_method'],
            totalAmount: $totalAmount,
        );

        foreach ($orders as $order) {
            $order->update(['idPayment' => $payment->idPayment]);
            $this->notificationService->sendOrderNotif($user->idUser, $order->idOrder, 'PENDING');
        }

        return redirect()->route('buyer.orders.show', $orders->first()->idOrder)
            ->with('success', 'Pesanan berhasil dibuat!');
    }
    /**
     * Proses checkout: buat order(s) + satu payment.
     *
     * Request structure:
     *   cart_item_ids[]          — item yang dibeli
     *   id_address               — alamat pengiriman
     *   id_method                — metode pembayaran
     *   service_price            — biaya layanan (flat)
     *   stores[{idStore}][id_store]
     *   stores[{idStore}][id_courier]
     *   stores[{idStore}][shipping_cost]
     *   stores[{idStore}][buyer_note]
     */
    public function placeOrder(CheckoutRequest $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated    = $request->validated();
        $cartItemIds  = $validated['cart_item_ids'];
        $storesData   = $validated['stores'];
        $servicePrice = (float) ($validated['service_price'] ?? 0);

        // Resolve shipping_cost dari COURIER_PRICES kalau frontend tidak kirim
        // (fallback safety — normalnya sudah dikirim via hidden input Alpine)
        foreach ($storesData as $idStore => &$storeData) {
            if (!isset($storeData['shipping_cost']) || $storeData['shipping_cost'] === '') {
                $storeData['shipping_cost'] = self::COURIER_PRICES[$storeData['id_courier']] ?? 0;
            }
        }
        unset($storeData);

        // Buat semua order (satu per toko) + satu payment, dalam satu transaksi
        $result = $this->orderService->checkoutMultiStore(
            userId:       $user->idUser,
            cartItemIds:  $cartItemIds,
            storesData:   $storesData,
            idAddress:    $validated['id_address'],
            servicePrice: $servicePrice,
        );

        $orders      = $result['orders'];
        $totalAmount = $result['grand_total'];

        // Buat satu Payment untuk semua order
        $payment = $this->paymentService->create(
            userId:      $user->idUser,
            methodId:    $validated['id_method'],
            totalAmount: $totalAmount,
        );

        // Hubungkan payment ke setiap order
        foreach ($orders as $order) {
            $order->update(['idPayment' => $payment->idPayment]);
            $this->notificationService->sendOrderNotif($user->idUser, $order->idOrder, 'PENDING');
        }

        if ($orders->count() === 1) {
            return redirect()->route('buyer.orders.show', $orders->first()->idOrder)
                ->with('success', 'Pesanan berhasil dibuat!');
        }

        return redirect()->route('buyer.orders.index')
            ->with('success', 'Pesanan berhasil dibuat! Kamu memiliki ' . $orders->count() . ' pesanan baru.');
    }

    /**
     * Selesaikan pesanan (buyer konfirmasi terima barang).
     */
    public function complete(string $id): RedirectResponse
    {
        $this->orderService->completeOrder($id, 'BUYER');

        return redirect()->route('buyer.orders.show', $id)
            ->with('success', 'Pesanan telah diterima. Dana berhasil diteruskan ke penjual!');
    }

    /**
     * Batalkan pesanan.
     */
    public function cancel(string $id): RedirectResponse
    {
        $this->orderService->updateStatus($id, 'CANCELLED', 'BUYER');

        return redirect()->route('buyer.orders.show', $id)
            ->with('success', 'Pesanan berhasil dibatalkan.');
    }
}
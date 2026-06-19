<?php

namespace App\Http\Controllers\Web\Buyer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\CheckoutRequest;
use App\Http\Requests\Payment\ConfirmPaymentRequest;
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
     * Halaman checkout (Dari Keranjang).
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
        $storeData = reset($storesData); 

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

        // REDIRECT KE HALAMAN PEMBAYARAN
        return redirect()->route('buyer.orders.payment', $orders->first()->idOrder)
            ->with('success', 'Pesanan berhasil dibuat! Silakan selesaikan pembayaran.');
    }

    /**
     * Proses checkout: buat order(s) dari keranjang.
     */
    public function placeOrder(CheckoutRequest $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated    = $request->validated();
        $cartItemIds  = $validated['cart_item_ids'];
        $storesData   = $validated['stores'];
        $servicePrice = (float) ($validated['service_price'] ?? 0);

        foreach ($storesData as $idStore => &$storeData) {
            if (!isset($storeData['shipping_cost']) || $storeData['shipping_cost'] === '') {
                $storeData['shipping_cost'] = self::COURIER_PRICES[$storeData['id_courier']] ?? 0;
            }
        }
        unset($storeData);

        $result = $this->orderService->checkoutMultiStore(
            userId:       $user->idUser,
            cartItemIds:  $cartItemIds,
            storesData:   $storesData,
            idAddress:    $validated['id_address'],
            servicePrice: $servicePrice,
        );

        $orders      = $result['orders'];
        $totalAmount = $result['grand_total'];

        $payment = $this->paymentService->create(
            userId:      $user->idUser,
            methodId:    $validated['id_method'],
            totalAmount: $totalAmount,
        );

        foreach ($orders as $order) {
            $order->update(['idPayment' => $payment->idPayment]);
            $this->notificationService->sendOrderNotif($user->idUser, $order->idOrder, 'PENDING');
        }

        if ($orders->count() === 1) {
            // REDIRECT KE HALAMAN PEMBAYARAN
            return redirect()->route('buyer.orders.payment', $orders->first()->idOrder)
                ->with('success', 'Pesanan berhasil dibuat! Silakan selesaikan pembayaran.');
        }

        return redirect()->route('buyer.orders.index')
            ->with('success', 'Pesanan berhasil dibuat! Kamu memiliki ' . $orders->count() . ' pesanan baru.');
    }

    /**
     * -----------------------------------------------------------
     * HALAMAN PEMBAYARAN (TRANSFER MANUAL KE REKENING PLATFORM)
     * -----------------------------------------------------------
     */

    /**
     * Menampilkan halaman instruksi pembayaran (info rekening + upload bukti)
     */
    public function payment(string $id): View|RedirectResponse
    {
        $order = $this->orderService->getById($id);

        // Jika status bukan PENDING, berarti sudah dibayar/dibatalkan. Lempar kembali ke detail.
        if ($order->orderStatus !== 'PENDING') {
            return redirect()->route('buyer.orders.show', $id);
        }

        // Pastikan relasi payment dan method-nya ikut dimuat
        $order->load(['payment.paymentMethod']);

        return view('buyer.orders.payment', compact('order'));
    }

    /**
     * Upload Bukti Transfer & Konfirmasi Pembayaran (Tanpa verifikasi admin)
     * Begitu bukti diupload, status order & payment langsung dianggap lunas.
     */
    public function confirmPayment(ConfirmPaymentRequest $request, string $id): RedirectResponse
    {
        try {
            $order = $this->orderService->getById($id);

            if ($order->orderStatus !== 'PENDING') {
                return redirect()->route('buyer.orders.show', $id);
            }

            // Simpan bukti transfer
            $this->paymentService->uploadProof($order->idPayment, $request->file('proof_image'));

            // Tanpa verifikasi admin: langsung dikonfirmasi begitu bukti diupload
            $this->paymentService->confirm($order->idPayment);

            return redirect()->route('buyer.orders.show', $id)
                ->with('success', 'Bukti transfer berhasil dikirim. Pembayaran telah dikonfirmasi!');

        } catch (\Exception $e) {
            // Kalau error, akan muncul di layar
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    /**
     * Konfirmasi Pesanan COD (Bayar di Tempat)
     * Tidak perlu upload bukti, karena pembayaran dilakukan saat barang diterima.
     */
    public function confirmCod(string $id): RedirectResponse
    {
        try {
            $order = $this->orderService->getById($id);

            if ($order->orderStatus !== 'PENDING') {
                return redirect()->route('buyer.orders.show', $id);
            }

            // COD: pesanan langsung diproses, pembayaran ditagih saat barang diterima
            $this->paymentService->confirm($order->idPayment);

            return redirect()->route('buyer.orders.show', $id)
                ->with('success', 'Pesanan dikonfirmasi! Pembayaran dilakukan saat barang diterima (COD).');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }
    
    /**
     * -----------------------------------------------------------
     */

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
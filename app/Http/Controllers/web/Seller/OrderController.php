<?php

namespace App\Http\Controllers\Web\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shipping\UpdateShippingRequest;
use App\Services\NotificationService;
use App\Services\OrderService;
use App\Services\ShippingService;
use App\Services\StoreService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct(
        private OrderService        $orderService,
        private ShippingService     $shippingService,
        private StoreService        $storeService,
        private NotificationService $notificationService,
    ) {}

    /**
     * Daftar semua order masuk ke toko seller.
     */
    public function index(): View
    {
        /** @var \App\Models\User $user */
        $user   = Auth::user();
        $store  = $this->storeService->getBySeller($user->idUser);
        $orders = $this->orderService->getByStore($store->idStore);

        return view('seller.orders.index', compact('orders'));
    }

    /**
     * Detail satu order.
     */
    public function show(string $id): View
    {
        $order = $this->orderService->getById($id);

        return view('seller.orders.show', compact('order'));
    }

    /**
     * Konfirmasi order — ubah status ke PROCESSED.
     */
    public function confirm(string $id): RedirectResponse
    {
        $order = $this->orderService->updateStatus($id, 'PROCESSED', 'SELLER');

        $this->notificationService->sendOrderNotif($order->idUser, $order->idOrder, 'PROCESSED');

        return redirect()->route('seller.orders.show', $id)
            ->with('success', 'Order berhasil dikonfirmasi.');
    }

    /**
     * Form input data pengiriman.
     */
    public function createShipping(string $orderId): View
    {
        $order    = $this->orderService->getById($orderId);
        $couriers = \App\Models\CourierOption::where('isActive', true)->get();

        return view('seller.orders.shipping', compact('order', 'couriers'));
    }

    /**
     * Simpan data pengiriman & ubah status order ke SHIPPED.
     */
    public function storeShipping(Request $request, string $orderId): RedirectResponse
    {
        $request->validate([
            'id_courier'     => ['required', 'string', 'exists:courier_options,idCourier'],
            'shipping_price' => ['required', 'numeric', 'min:0'],
        ]);

        $this->shippingService->create(
            $orderId,
            $request->input('id_courier'),
            $request->input('shipping_price'),
        );

        $order = $this->orderService->getById($orderId);
        $this->notificationService->sendOrderNotif($order->idUser, $orderId, 'SHIPPED');

        return redirect()->route('seller.orders.show', $orderId)
            ->with('success', 'Data pengiriman berhasil disimpan.');
    }

    /**
     * Update status pengiriman.
     */
    public function updateShipping(UpdateShippingRequest $request, string $shippingId): RedirectResponse
    {
        $shipping = $this->shippingService->updateStatus(
            $shippingId,
            $request->input('status'),
            $request->only(['location', 'description']),
        );

        return redirect()->route('seller.orders.show', $shipping->idOrder)
            ->with('success', 'Status pengiriman berhasil diperbarui.');
    }
}
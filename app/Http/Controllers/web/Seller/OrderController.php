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
     * Bisa difilter lewat query string ?status=PENDING dst.
     */
    public function index(Request $request): View
    {
        /** @var \App\Models\User $user */
        $user   = Auth::user();
        $store  = $this->storeService->getBySeller($user->idUser);

        $status = $request->query('status');

        $orders       = $this->orderService->getByStore($store->idStore, $status);
        $statusCounts = $this->orderService->getStatusCounts($store->idStore);

        return view('seller.orders.index', compact('orders', 'statusCounts'));
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
     * Batalkan order — ubah status ke CANCELLED.
     */
    public function cancel(string $id): RedirectResponse
    {
        $order = $this->orderService->updateStatus($id, 'CANCELLED', 'SELLER');

        $this->notificationService->sendOrderNotif($order->idUser, $order->idOrder, 'CANCELLED');

        return redirect()->route('seller.orders.show', $id)
            ->with('success', 'Order berhasil dibatalkan.');
    }

    /**
     * Konfirmasi pengiriman.
     * Data shipping (kurir, dll) sudah dibuat sejak buyer checkout.
     * Seller cukup klik konfirmasi -> generate resi & tanggal kirim, status order jadi SHIPPED.
     */
    public function confirmShipping(string $orderId): RedirectResponse
    {
        $this->shippingService->confirm($orderId);

        $order = $this->orderService->getById($orderId);
        $this->notificationService->sendOrderNotif($order->idUser, $orderId, 'SHIPPED');

        return redirect()->route('seller.orders.show', $orderId)
            ->with('success', 'Pengiriman berhasil dikonfirmasi.');
    }

    /**
     * Update status pengiriman (dari WAITING/PICKED_UP/IN_TRANSIT/dst).
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
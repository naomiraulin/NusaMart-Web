<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderRepository
{
    /**
     * Ambil semua order milik user (riwayat belanja buyer).
     */
    public function findByUser(string $userId): LengthAwarePaginator
    {
        return Order::with([
            'orderItems.productItem.product.productImages',
            'store',
            'shipping',
        ])
            ->where('idUser', $userId)
            ->orderBy('orderDate', 'desc')
            ->paginate(10);
    }

    /**
     * Ambil semua order yang masuk ke store (untuk seller).
     * Bisa difilter berdasarkan status order.
     */
    public function findByStore(string $storeId, ?string $status = null): LengthAwarePaginator
    {
        return Order::with([
            'orderItems.productItem.product',
            'shipping',
        ])
            ->where('idStore', $storeId)
            ->when($status, fn ($query) => $query->where('orderStatus', $status))
            ->orderBy('orderDate', 'desc')
            ->paginate(10)
            ->withQueryString();
    }

    /**
     * Hitung jumlah order per status untuk satu store.
     * Dipakai untuk badge angka di tab filter.
     */
    public function countByStatusForStore(string $storeId): array
    {
        return Order::where('idStore', $storeId)
            ->selectRaw('orderStatus, count(*) as total')
            ->groupBy('orderStatus')
            ->pluck('total', 'orderStatus')
            ->toArray();
    }

    /**
     * Ambil detail satu order.
     */
    public function findById(string $id)
    {
        return Order::with([
            'orderItems.productItem.product.productImages',
            'store',
            'shipping.courier',
            'shipping.shippingTrackings',
            'address',
            'payment.paymentMethod',
        ])->where('idOrder', $id)->first();
    }

    /**
     * Buat order baru.
     */
    public function create(array $data): Order
    {
        return Order::create($data);
    }

    /**
     * Update status order.
     */
    public function updateStatus(string $id, string $status): Order
    {
        $order = Order::where('idOrder', $id)->firstOrFail();
        $order->update(['orderStatus' => $status]);

        return $order->fresh();
    }
}
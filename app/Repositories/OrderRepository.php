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
     */
    public function findByStore(string $storeId): LengthAwarePaginator
    {
        return Order::with([
            'orderItems.productItem.product',
            'shipping',
        ])
            ->where('idStore', $storeId)
            ->orderBy('orderDate', 'desc')
            ->paginate(10);
    }

    /**
     * Ambil detail satu order.
     */
    public function findById(string $id)
    {
        return Order::with([
            'orderItems.productItem.product.productImages', 
            'store',
            'shipping.courier',           // <--- INI HARUS BERUBAH JADI courier
            'shipping.shippingTrackings',
            'address',                    // <--- INI HARUS BERUBAH JADI address
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
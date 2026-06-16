<?php

namespace App\Repositories;

use App\Models\Shipping;
use App\Models\ShippingTracking;

class ShippingRepository
{
    /**
     * Ambil data shipping berdasarkan ID order.
     */
    public function findByOrder(string $orderId): ?Shipping
    {
        return Shipping::with(['courierOption', 'shippingTrackings'])
            ->where('idOrder', $orderId)
            ->first();
    }

    /**
     * Ambil detail shipping berdasarkan ID.
     */
    public function findById(string $id): ?Shipping
    {
        return Shipping::with(['courierOption', 'shippingTrackings'])
            ->where('idShipping', $id)
            ->first();
    }

    /**
     * Buat data shipping baru setelah order diproses seller.
     */
    public function create(array $data): Shipping
    {
        return Shipping::create($data);
    }

    /**
     * Update status pengiriman.
     */
    public function updateStatus(string $id, string $status): Shipping
    {
        $shipping = Shipping::where('idShipping', $id)->firstOrFail();
        $shipping->update(['shippingStatus' => $status]);

        return $shipping->fresh();
    }

    /**
     * Tambah tracking point baru.
     */
    public function addTracking(string $shippingId, array $data): ShippingTracking
    {
        return ShippingTracking::create([
            'idShipping'      => $shippingId,
            'packetLocation'  => $data['packetLocation'] ?? null,
            'description'     => $data['description'] ?? null,
        ]);
    }
}
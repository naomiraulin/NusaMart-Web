<?php

namespace App\Repositories;

use App\Models\Shipping;
use App\Models\ShippingTracking;
use App\Services\IdGeneratorService;

class ShippingRepository
{
    public function __construct(
        protected IdGeneratorService $idGenerator
    ) {}
    /**
     * Ambil data shipping berdasarkan ID order.
     */
    public function findByOrder(string $orderId): ?Shipping
    {
        return Shipping::with(['courier', 'shippingTrackings'])
            ->where('idOrder', $orderId)
            ->first();
    }

    /**
     * Ambil detail shipping berdasarkan ID.
     */
    public function findById(string $id): ?Shipping
    {
        return Shipping::with(['courier', 'shippingTrackings'])
            ->where('idShipping', $id)
            ->first();
    }

    /**
     * Buat data shipping baru setelah order diproses seller.
     * (Tidak dipakai lagi untuk flow normal, karena shipping sudah dibuat saat checkout buyer.
     * Disisakan untuk kasus shipping belum ada / data lama.)
     */
    public function create(array $data): Shipping
    {
        return Shipping::create($data);
    }

    /**
     * Lengkapi data shipping yang sudah ada (dibuat saat checkout buyer)
     * dengan resi & tanggal kirim, lalu set status WAITING -> PICKED_UP/lanjut.
     */
    public function confirm(string $id, array $data): Shipping
    {
        $shipping = Shipping::where('idShipping', $id)->firstOrFail();
        $shipping->update($data);

        return $shipping->fresh();
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
            'idTracking'      => $this->idGenerator->generate('TRK', ShippingTracking::class, 'idTracking'),
            'idShipping'      => $shippingId,
            'packetLocation'  => $data['packetLocation'] ?? 'Gudang Penjual',
            'description'     => $data['description'] ?? 'Pesanan telah dikonfirmasi dan siap diambil kurir.',
        ]);
    }
}
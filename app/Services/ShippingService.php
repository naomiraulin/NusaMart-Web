<?php

namespace App\Services;

use App\Models\Shipping;
use App\Models\ShippingTracking;
use App\Repositories\OrderRepository;
use App\Repositories\ShippingRepository;
use App\Services\IdGeneratorService;
use Illuminate\Support\Facades\DB;

class ShippingService
{
    public function __construct(
        private ShippingRepository $shippingRepository,
        private OrderRepository    $orderRepository,
        private IdGeneratorService $idGenerator,
    ) {}

    /**
     * Ambil data shipping berdasarkan order.
     */
    public function getByOrder(string $orderId): ?Shipping
    {
        return $this->shippingRepository->findByOrder($orderId);
    }

    /**
     * Konfirmasi pengiriman: shipping record sudah ada sejak buyer checkout
     * (kurir sudah dipilih, status WAITING, resi & shippingDate masih kosong).
     * Seller cukup klik konfirmasi -> generate resi & tanggal kirim, ubah status order ke SHIPPED.
     */
    public function confirm(string $orderId): Shipping
    {
        return DB::transaction(function () use ($orderId) {
            $shipping = $this->shippingRepository->findByOrder($orderId);

            if (!$shipping) {
                abort(404, 'Data pengiriman untuk order ini tidak ditemukan.');
            }

            $shipping = $this->shippingRepository->confirm($shipping->idShipping, [
                'resi'           => $shipping->resi ?? $this->generateResi(),
                'shippingDate'   => now(),
                'shippingStatus' => 'PICKED_UP',
            ]);

            $this->shippingRepository->addTracking($shipping->idShipping, [
                'description' => 'Pesanan telah dikonfirmasi dan siap diambil kurir.',
            ]);

            // Update status order jadi SHIPPED
            $this->orderRepository->updateStatus($orderId, 'SHIPPED');

            return $shipping;
        });
    }

    /**
     * Buat data shipping baru secara manual.
     * Disisakan untuk kasus shipping belum ada sama sekali (data lama / fallback).
     */
    public function create(string $orderId, string $courierId, float $shippingPrice): Shipping
    {
        return DB::transaction(function () use ($orderId, $courierId, $shippingPrice) {
            $shipping = $this->shippingRepository->create([
                'idShipping'     => $this->idGenerator->generate('SHP', Shipping::class, 'idShipping'),
                'idOrder'        => $orderId,
                'idCourier'      => $courierId,
                'resi'           => $this->generateResi(),
                'shippingPrice'  => $shippingPrice,
                'shippingDate'   => now(),
                'shippingStatus' => 'WAITING',
            ]);

            $this->shippingRepository->addTracking($shipping->idShipping, [
                'description' => 'Pesanan sedang disiapkan oleh seller.',
            ]);

            $this->orderRepository->updateStatus($orderId, 'SHIPPED');

            return $shipping;
        });
    }

    /**
     * Update status pengiriman & tambah tracking point.
     * Hanya untuk status proses pengiriman (WAITING/PICKED_UP/IN_TRANSIT/FAILED).
     * Status DELIVERED tidak ditangani di sini — itu murni aksi buyer lewat
     * OrderService::completeOrder(), karena sekaligus mencairkan dana ke wallet seller.
     */
    public function updateStatus(string $shippingId, string $status, array $trackingData = []): Shipping
    {
        return DB::transaction(function () use ($shippingId, $status, $trackingData) {
            $shipping = $this->shippingRepository->updateStatus($shippingId, $status);

            $this->shippingRepository->addTracking($shippingId, [
                'packetLocation' => $trackingData['location'] ?? null,
                'description'    => $trackingData['description'] ?? "Status diperbarui: {$status}",
            ]);

            return $shipping->fresh();
        });
    }

    /**
     * Ambil semua tracking point untuk satu pengiriman.
     */
    public function getTracking(string $shippingId): Shipping
    {
        $shipping = $this->shippingRepository->findById($shippingId);

        if (!$shipping) {
            abort(404, 'Data pengiriman tidak ditemukan.');
        }

        return $shipping;
    }

    /**
     * Generate nomor resi unik.
     */
    private function generateResi(): string
    {
        return 'NSM' . strtoupper(substr(uniqid(), -8));
    }
}
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
     * Buat data shipping setelah seller konfirmasi order.
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
                'shippingStatus' => 'WAITING',
            ]);

            // Tambah tracking awal
            $this->shippingRepository->addTracking($shipping->idShipping, [
                'description' => 'Pesanan sedang disiapkan oleh seller.',
            ]);

            // Update status order jadi SHIPPED
            $this->orderRepository->updateStatus($orderId, 'SHIPPED');

            return $shipping;
        });
    }

    /**
     * Update status pengiriman & tambah tracking point.
     */
    public function updateStatus(string $shippingId, string $status, array $trackingData = []): Shipping
    {
        return DB::transaction(function () use ($shippingId, $status, $trackingData) {
            $shipping = $this->shippingRepository->updateStatus($shippingId, $status);

            $this->shippingRepository->addTracking($shippingId, [
                'packetLocation' => $trackingData['location'] ?? null,
                'description'    => $trackingData['description'] ?? "Status diperbarui: {$status}",
            ]);

            // Kalau sudah delivered, update order juga
            if ($status === 'DELIVERED') {
                $this->orderRepository->updateStatus($shipping->idOrder, 'DELIVERED');

                // Update tanggal tiba
                \App\Models\Order::where('idOrder', $shipping->idOrder)
                    ->update(['arrivedDate' => now()]);
            }

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
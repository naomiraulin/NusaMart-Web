<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CourierOption;
use App\Models\Shipping;
use App\Models\ShippingTracking;
use App\Services\IdGeneratorService;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function __construct(
        private IdGeneratorService $idGenerator
    ) {}

    // GET /api/couriers → semua kurir aktif
    public function couriers()
    {
        $couriers = CourierOption::where('isActive', true)->get();
        return response()->json($couriers);
    }

    // GET /api/couriers/{id} → detail kurir
    public function courierDetail(string $id)
    {
        $courier = CourierOption::where('idCourier', $id)->firstOrFail();
        return response()->json($courier);
    }

    // GET /api/shipping/order/{orderId} → shipping by order
    public function byOrder(string $orderId)
    {
        $shipping = Shipping::where('idOrder', $orderId)
            ->with('shippingTrackings')
            ->firstOrFail();

        return response()->json($shipping);
    }

    // POST /api/seller/shipping → buat shipping baru (seller konfirmasi pesanan)
    public function store(Request $request)
    {
        $request->validate([
            'idOrder'    => 'required|string',
            'idCourier'  => 'required|string',
        ]);

        // Cek apakah sudah ada shipping untuk order ini
        $existing = Shipping::where('idOrder', $request->idOrder)->first();
        if ($existing) {
            return response()->json([
                'message' => 'Data pengiriman untuk pesanan ini sudah dibuat.'
            ], 422);
        }

        $shipping = Shipping::create([
            'idShipping'     => $this->idGenerator->generate('SHP', Shipping::class, 'idShipping'),
            'idOrder'        => $request->idOrder,
            'idCourier'      => $request->idCourier,
            'resi'           => null,
            'shippingPrice'  => 0,
            'shippingStatus' => 'WAITING',
            'shippingDate'   => null,
            'deliveredDate'  => null,
        ]);

        return response()->json([
            'message'  => 'Data pengiriman berhasil dibuat',
            'shipping' => $shipping,
        ], 201);
    }

    // PUT /api/seller/shipping/{id}/status → update status pengiriman
    public function updateStatus(Request $request, string $id)
    {
        $request->validate([
            'shippingStatus' => 'required|in:WAITING,PICKED_UP,IN_TRANSIT,DELIVERED,FAILED',
            'resi'           => 'sometimes|nullable|string',
        ]);

        $shipping = Shipping::where('idShipping', $id)->firstOrFail();

        $shipping->update([
            'shippingStatus' => $request->shippingStatus,
            'resi'           => $request->resi ?? $shipping->resi,
            'shippingDate'   => $request->shippingStatus === 'PICKED_UP' && !$shipping->shippingDate
                                    ? now() : $shipping->shippingDate,
            'deliveredDate'  => $request->shippingStatus === 'DELIVERED' && !$shipping->deliveredDate
                                    ? now() : $shipping->deliveredDate,
        ]);

        return response()->json([
            'message'  => 'Status pengiriman berhasil diupdate',
            'shipping' => $shipping,
        ]);
    }

    // GET /api/shipping/{id}/tracking → riwayat tracking
    public function tracking(string $id)
    {
        $trackings = ShippingTracking::where('idShipping', $id)
            ->orderByDesc('updateAt')
            ->get();

        return response()->json($trackings);
    }

    // POST /api/seller/shipping/{id}/tracking → tambah log tracking
    public function addTracking(Request $request, string $id)
    {
        $request->validate([
            'packetLocation' => 'sometimes|nullable|string',
            'description'    => 'required|string',
        ]);

        $tracking = ShippingTracking::create([
            'idTracking'     => $this->idGenerator->generate('TRK', ShippingTracking::class, 'idTracking'),
            'idShipping'     => $id,
            'packetLocation' => $request->packetLocation,
            'description'    => $request->description,
            'updateAt'       => now(),
        ]);

        return response()->json([
            'message'  => 'Tracking berhasil ditambahkan',
            'tracking' => $tracking,
        ], 201);
    }
}
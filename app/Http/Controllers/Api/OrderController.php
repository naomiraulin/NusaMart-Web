<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\IdGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function __construct(
        private IdGeneratorService $idGenerator
    ) {}

    // GET /api/orders → semua order milik buyer
    public function index(Request $request)
    {
        $orders = Order::where('idUser', $request->user()->idUser)
            ->with('orderItems')
            ->orderByDesc('createAt')
            ->get();

        return response()->json($orders);
    }

    // GET /api/orders/{id} → detail order
    public function show(Request $request, string $id)
    {
        $order = Order::where('idOrder', $id)
            ->where('idUser', $request->user()->idUser)
            ->with('orderItems')
            ->firstOrFail();

        return response()->json($order);
    }

    // POST /api/orders → buat order baru
    public function store(Request $request)
    {
        $request->validate([
            'idStore'      => 'required|string',
            'idAddress'    => 'required|string',
            'idPayment'    => 'required|string',
            'shippingCost' => 'required|numeric',
            'servicePrice' => 'required|numeric',
            'buyerNote'    => 'sometimes|nullable|string',
            'items'        => 'required|array|min:1',
            'items.*.idItem'        => 'required|string',
            'items.*.quantity'      => 'required|integer|min:1',
            'items.*.nameSnapshot'  => 'required|string',
            'items.*.priceSnapshot' => 'required|numeric',
        ]);

        $productTotalPrice = collect($request->items)
            ->sum(fn($item) => $item['priceSnapshot'] * $item['quantity']);

        $grandTotal = $productTotalPrice + $request->shippingCost + $request->servicePrice;

        $newOrderId  = $this->idGenerator->generate('ORD', Order::class, 'idOrder');
        $invoiceNumber = 'INV/' . date('Ymd') . '/' . $newOrderId;

        $order = Order::create([
            'idOrder'           => $newOrderId,
            'idPayment'         => $request->idPayment,
            'idUser'            => $request->user()->idUser,
            'idStore'           => $request->idStore,
            'idAddress'         => $request->idAddress,
            'invoiceNumber'     => $invoiceNumber,
            'orderDate'         => now(),
            'arrivedDate'       => null,
            'orderStatus'       => 'PENDING',
            'productTotalPrice' => $productTotalPrice,
            'shippingCost'      => $request->shippingCost,
            'servicePrice'      => $request->servicePrice,
            'grandTotal'        => $grandTotal,
            'buyerNote'         => $request->buyerNote,
            'createAt'          => now(),
            'updateAt'          => now(),
        ]);

        // Simpan order items
        foreach ($request->items as $item) {
            OrderItem::create([
                'idOrderItem'   => $this->idGenerator->generate('OIT', OrderItem::class, 'idOrderItem'),
                'idOrder'       => $order->idOrder,
                'idItem'        => $item['idItem'],
                'quantity'      => $item['quantity'],
                'nameSnapshot'  => $item['nameSnapshot'],
                'priceSnapshot' => $item['priceSnapshot'],
            ]);
        }

        return response()->json([
            'message' => 'Pesanan berhasil dibuat',
            'order'   => $order->load('orderItems'),
        ], 201);
    }

    // PUT /api/orders/{id}/cancel → batalkan order (Buyer)
    public function cancel(Request $request, string $id)
    {
        $order = Order::where('idOrder', $id)
            ->where('idUser', $request->user()->idUser)
            ->firstOrFail();

        if (!in_array($order->orderStatus, ['PENDING', 'PROCESSED'])) {
            return response()->json([
                'message' => 'Pesanan tidak bisa dibatalkan.'
            ], 422);
        }

        $order->update([
            'orderStatus' => 'CANCELLED',
            'updateAt'    => now(),
        ]);

        return response()->json([
            'message' => 'Pesanan berhasil dibatalkan',
            'order'   => $order,
        ]);
    }

    // PUT /api/orders/{id}/status → update status order (Seller/Admin)
    public function updateStatus(Request $request, string $id)
    {
        $request->validate([
            'orderStatus' => 'required|in:PENDING,PROCESSED,SHIPPED,DELIVERED,CANCELLED',
        ]);

        $order = Order::where('idOrder', $id)->firstOrFail();

        $order->update([
            'orderStatus' => $request->orderStatus,
            'arrivedDate' => $request->orderStatus === 'DELIVERED' ? now() : $order->arrivedDate,
            'updateAt'    => now(),
        ]);

        return response()->json([
            'message' => 'Status pesanan berhasil diupdate',
            'order'   => $order,
        ]);
    }

    // GET /api/seller/orders → semua order masuk ke toko seller
    public function sellerOrders(Request $request)
    {
        $store = \App\Models\Store::where('idSeller', $request->user()->idUser)->firstOrFail();

        $orders = Order::where('idStore', $store->idStore)
            ->with('orderItems')
            ->orderByDesc('createAt')
            ->get();

        return response()->json($orders);
    }

    // GET /api/orders/{id}/reviewed → cek apakah order sudah direview
    public function isReviewed(string $id)
    {
        $orderItemIds = OrderItem::where('idOrder', $id)->pluck('idOrderItem');

        $hasReview = \App\Models\Review::whereIn('idOrderItem', $orderItemIds)->exists();

        return response()->json(['isReviewed' => $hasReview]);
    }
}
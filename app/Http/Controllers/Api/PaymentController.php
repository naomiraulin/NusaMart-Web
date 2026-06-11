<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Order;
use App\Services\IdGeneratorService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(
        private IdGeneratorService $idGenerator
    ) {}

    // GET /api/payments/methods → semua metode pembayaran aktif
    public function methods()
    {
        $methods = PaymentMethod::where('isActive', true)->get();
        return response()->json($methods);
    }

    // GET /api/payments/{id} → detail payment
    public function show(string $id)
    {
        $payment = Payment::where('idPayment', $id)
            ->where('idUser', request()->user()->idUser)
            ->firstOrFail();

        return response()->json($payment);
    }

    // GET /api/payments/order/{orderId} → payment by order
    public function byOrder(string $orderId)
    {
        $order = Order::where('idOrder', $orderId)
            ->where('idUser', request()->user()->idUser)
            ->firstOrFail();

        $payment = Payment::where('idPayment', $order->paymentId)->first();

        return response()->json($payment);
    }

    // POST /api/payments → buat payment baru
    public function store(Request $request)
    {
        $request->validate([
            'idMethod'             => 'required|string',
            'totalAmount'          => 'required|numeric',
            'transactionIdGateway' => 'sometimes|nullable|string',
            'snapToken'            => 'sometimes|nullable|string',
            'imageURL'             => 'sometimes|nullable|string',
        ]);

        $payment = Payment::create([
            'idPayment'            => $this->idGenerator->generate('PAY', Payment::class, 'idPayment'),
            'idUser'               => $request->user()->idUser,
            'idMethod'             => $request->idMethod,
            'totalAmount'          => $request->totalAmount,
            'transactionIdGateway' => $request->transactionIdGateway,
            'snapToken'            => $request->snapToken,
            'paymentStatus'        => 'PENDING',
            'paymentTime'          => null,
            'imageURL'             => $request->imageURL,
            'createAt'             => now(),
            'updateAt'             => now(),
        ]);

        return response()->json([
            'message' => 'Payment berhasil dibuat',
            'payment' => $payment,
        ], 201);
    }

    // PUT /api/payments/{id}/status → update status payment
    public function updateStatus(Request $request, string $id)
    {
        $request->validate([
            'paymentStatus' => 'required|in:PENDING,APPROVED,CANCELED',
        ]);

        $payment = Payment::where('idPayment', $id)
            ->where('idUser', $request->user()->idUser)
            ->firstOrFail();

        $payment->update([
            'paymentStatus' => $request->paymentStatus,
            'paymentTime'   => $request->paymentStatus === 'APPROVED' ? now() : $payment->paymentTime,
            'updateAt'      => now(),
        ]);

        return response()->json([
            'message' => 'Status payment berhasil diupdate',
            'payment' => $payment,
        ]);
    }
}
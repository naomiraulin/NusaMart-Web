<?php

namespace App\Repositories;

use App\Models\Payment;

class PaymentRepository
{
    /**
     * Ambil data payment berdasarkan ID.
     */
    public function findById(string $id): ?Payment
    {
        return Payment::with('paymentMethod')
            ->where('idPayment', $id)
            ->first();
    }

    /**
     * Ambil payment berdasarkan ID order.
     */
    public function findByOrder(string $orderId): ?Payment
    {
        return Payment::with('paymentMethod')
            ->whereHas('orders', fn($q) => $q->where('paymentId', $orderId))
            ->first();
    }

    /**
     * Buat data payment baru.
     */
    public function create(array $data): Payment
    {
        return Payment::create($data);
    }

    /**
     * Update status pembayaran.
     */
    public function updateStatus(string $id, string $status): Payment
    {
        $payment = Payment::where('idPayment', $id)->firstOrFail();
        $payment->update(['paymentStatus' => $status]);

        return $payment->fresh();
    }

    /**
     * Update snap token (Midtrans).
     */
    public function updateSnapToken(string $id, string $snapToken): Payment
    {
        $payment = Payment::where('idPayment', $id)->firstOrFail();
        $payment->update(['snapToken' => $snapToken]);

        return $payment->fresh();
    }
}
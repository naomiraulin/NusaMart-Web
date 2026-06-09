<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Payment;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $orders = Order::all();
        $methods = PaymentMethod::all();

        // Pastikan ada order dan metode pembayaran sebelum mengeksekusi
        if ($orders->count() > 0 && $methods->count() > 0) {
            foreach ($orders as $order) {
                $randomMethod = $methods->random();

                $payment = Payment::factory()->create([
                    'idUser'      => $order->idUser,  // ← tambah ini
                    'idMethod'    => $randomMethod->idMethod,
                    'totalAmount' => $order->grandTotal, // ← ganti dari amount
                ]);

                $order->update([
                    'paymentId' => $payment->idPayment
                ]);
            }
        }
    }
}
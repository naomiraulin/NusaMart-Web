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
                // Pilih metode pembayaran secara acak
                $randomMethod = $methods->random();
                
                // 1. Buat data pembayaran yang nominalnya persis dengan grandTotal pesanan
                $payment = Payment::factory()->create([
                    'idMethod' => $randomMethod->idMethod,
                    'amount' => $order->grandTotal,
                ]);

                // 2. Update data Order agar terhubung ke Payment yang baru saja dibuat
                $order->update([
                    'paymentId' => $payment->idPayment
                ]);
            }
        }
    }
}
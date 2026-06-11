<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Payment;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua data Order dan Metode Pembayaran yang valid
        $orders = Order::all();
        $methods = PaymentMethod::all();

        // Pastikan ada order dan metode pembayaran sebelum mengeksekusi
        if ($orders->count() > 0 && $methods->count() > 0) {
            $counter = 1;

            foreach ($orders as $order) {
                // Pilih metode pembayaran secara acak untuk setiap order
                $randomMethod = $methods->random();

                // Acak status pembayaran agar data testing lebih realistis untuk aplikasi UMKM
                $statusOptions = ['PENDING', 'APPROVED', 'FAILED'];
                $status = $statusOptions[array_rand($statusOptions)];

                // Buat record pembayaran
                $payment = Payment::create([
                    'idPayment'            => 'PAY-' . str_pad($counter++, 6, '0', STR_PAD_LEFT),
                    'idOrder'              => $order->idOrder,
                    'idMethod'             => $randomMethod->idMethod,
                    
                    // Simulasi ID dari Payment Gateway (misal Midtrans) jika statusnya APPROVED
                    'transactionIdGateway' => $status === 'APPROVED' ? 'TRX-' . rand(100000, 999999) : null,
                    'snapToken'            => null,
                    'paymentStatus'        => $status,
                    
                    // Isi tanggal bayar hanya jika statusnya APPROVED
                    'paymentTime'          => $status === 'APPROVED' ? Carbon::now()->subHours(rand(1, 24)) : null,
                    
                    // Tarik data langsung dari relasi Order
                    'idUser'               => $order->idUser,
                    'totalAmount'          => $order->grandTotal, 
                ]);

                // Update Order agar memiliki relasi balik ke Payment ini
                $order->update([
                    'paymentId' => $payment->idPayment
                ]);
            }
        }
    }
}
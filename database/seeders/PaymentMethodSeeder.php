<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            // --- E-Wallet & QRIS ---
            [
                'idMethod' => 'PAY-QRIS',
                'category' => 'E-Wallet & QRIS',
                'methodName' => 'QRIS',
                'description' => 'Bayar otomatis pakai E-Wallet',
                'provider' => 'MIDTRANS',
                'isActive' => true,
            ],
            
            // --- Transfer Bank (MANUAL) ---
            [
                'idMethod' => 'PAY-BCA',
                'category' => 'Transfer Bank',
                'methodName' => 'BCA',
                'description' => null,
                'provider' => 'MANUAL',
                'isActive' => true,
            ],
            [
                'idMethod' => 'PAY-BRI',
                'category' => 'Transfer Bank',
                'methodName' => 'BRI',
                'description' => null,
                'provider' => 'MANUAL',
                'isActive' => true,
            ],
            [
                'idMethod' => 'PAY-BNI',
                'category' => 'Transfer Bank',
                'methodName' => 'BNI',
                'description' => null,
                'provider' => 'MANUAL',
                'isActive' => true,
            ],
            [
                'idMethod' => 'PAY-MANDIRI',
                'category' => 'Transfer Bank',
                'methodName' => 'MANDIRI',
                'description' => null,
                'provider' => 'MANUAL',
                'isActive' => true,
            ],
            
            // --- Bayar di Tempat ---
            [
                'idMethod' => 'PAY-COD',
                'category' => 'Bayar di Tempat',
                'methodName' => 'Bayar di Tempat (COD)',
                'description' => 'Bayar tunai ke kurir saat pesanan tiba',
                'provider' => 'COD',
                'isActive' => true,
            ],
        ];

        foreach ($methods as $method) {
            PaymentMethod::create($method);
        }
    }
}
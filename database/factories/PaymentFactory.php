<?php

namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PaymentFactory extends Factory
{
    public function definition(): array
    {
        $status = fake()->randomElement(['PENDING', 'APPROVED', 'CANCELED']);
        
        return [
            'idPayment' => Str::uuid()->toString(),
            // idMethod akan disuntik dari seeder
            'transactionIdGateway' => Str::uuid()->toString(), // Simulasi ID Midtrans
            'snapToken' => Str::random(24), // Simulasi token
            'paymentStatus' => $status,
            'paymentTime' => ($status === 'APPROVED') ? fake()->dateTimeBetween('-1 month', 'now') : null,
            'imageURL' => ($status === 'APPROVED') ? fake()->imageUrl(640, 480, 'receipt', true) : null,
        ];
    }
}
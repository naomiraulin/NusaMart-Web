<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Services\IdGeneratorService;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PaymentFactory extends Factory
{
    public function definition(): array
    {
        $status = fake()->randomElement(['PENDING', 'APPROVED', 'CANCELED']);

        return [
            'idPayment'            => app(IdGeneratorService::class)->generate('PAY', Payment::class, 'idPayment'),
            'transactionIdGateway' => Str::uuid()->toString(),
            'snapToken'            => Str::random(24),
            'paymentStatus'        => $status,
            'paymentTime'          => ($status === 'APPROVED') ? fake()->dateTimeBetween('-1 month', 'now') : null,
            'imageURL'             => ($status === 'APPROVED') ? fake()->imageUrl(640, 480, 'receipt', true) : null,
        ];
    }
}
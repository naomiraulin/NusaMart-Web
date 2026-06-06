<?php

namespace Database\Factories;

use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PaymentMethodFactory extends Factory
{
    public function definition(): array
    {
        return [
            'idMethod' => Str::uuid()->toString(),
            'methodName' => fake()->words(2, true),
            'provider' => fake()->randomElement(['MIDTRANS', 'MANUAL', 'COD']),
            'isActive' => true,
        ];
    }
}
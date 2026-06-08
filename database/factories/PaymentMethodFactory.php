<?php

namespace Database\Factories;

use App\Models\PaymentMethod;
use App\Services\IdGeneratorService;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentMethodFactory extends Factory
{
    public function definition(): array
    {
        return [
            'idMethod'   => app(IdGeneratorService::class)->generate('MET', PaymentMethod::class, 'idMethod'),
            'methodName' => fake()->words(2, true),
            'provider'   => fake()->randomElement(['MIDTRANS', 'MANUAL', 'COD']),
            'isActive'   => true,
        ];
    }
}
<?php

namespace Database\Factories;

use App\Models\CourierOption;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CourierOptionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'idCourier' => Str::uuid()->toString(),
            'courierName' => fake()->company() . ' Express',
            'serviceType' => fake()->randomElement(['REGULER', 'KARGO', 'INSTANT']),
            'timeEstimation' => fake()->numberBetween(1, 7),
            'isActive' => true,
        ];
    }
}
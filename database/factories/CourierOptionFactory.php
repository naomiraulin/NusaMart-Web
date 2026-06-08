<?php

namespace Database\Factories;

use App\Models\CourierOption;
use App\Services\IdGeneratorService;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourierOptionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'idCourier'      => app(IdGeneratorService::class)->generate('CUR', CourierOption::class, 'idCourier'),
            'courierName'    => fake()->company() . ' Express',
            'serviceType'    => fake()->randomElement(['REGULER', 'KARGO']),
            'timeEstimation' => fake()->numberBetween(1, 7),
            'isActive'       => true,
        ];
    }
}
<?php

namespace Database\Factories;

use App\Models\OrderItem;
use App\Services\IdGeneratorService;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'idOrderItem'   => app(IdGeneratorService::class)->generate('OIT', OrderItem::class, 'idOrderItem'),
            'nameSnapshot'  => fake()->words(3, true),
            'priceSnapshot' => fake()->randomFloat(2, 10000, 500000),
            'quantity'      => fake()->numberBetween(1, 5),
        ];
    }
}
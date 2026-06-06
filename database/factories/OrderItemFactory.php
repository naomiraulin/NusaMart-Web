<?php

namespace Database\Factories;

use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'idOrderItem' => Str::uuid()->toString(),
            // idOrder, idItem, nameSnapshot, priceSnapshot akan disuntikkan dari Seeder agar valid
            'nameSnapshot' => fake()->words(3, true),
            'priceSnapshot' => fake()->randomFloat(2, 10000, 500000),
            'quantity' => fake()->numberBetween(1, 5),
        ];
    }
}
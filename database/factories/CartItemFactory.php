<?php

namespace Database\Factories;

use App\Models\CartItem;
use App\Services\IdGeneratorService;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'idCartItem' => app(IdGeneratorService::class)->generate('CIT', CartItem::class, 'idCartItem'),
            'quantity'   => fake()->numberBetween(1, 5),
            'createAt'   => now(),
        ];
    }
}
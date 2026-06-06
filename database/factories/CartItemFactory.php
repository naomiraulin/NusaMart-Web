<?php

namespace Database\Factories;

use App\Models\CartItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CartItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'idCartItem' => Str::uuid()->toString(),
            // idCart dan idItem akan diisi via Seeder
            'quantity' => fake()->numberBetween(1, 5), // Asumsi user beli 1 sampai 5 barang per item
        ];
    }
}
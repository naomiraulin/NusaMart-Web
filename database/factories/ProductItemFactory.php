<?php

namespace Database\Factories;

use App\Models\ProductItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'idItem' => Str::uuid()->toString(),
            // idProduct diisi lewat Seeder
            'sku' => fake()->unique()->bothify('SKU-####-????'), // Contoh output: SKU-1234-ABCD
            'stock' => fake()->numberBetween(0, 200),
            'price' => fake()->numberBetween(10000, 500000), // Asumsi harga dalam Rupiah
            'isActive' => fake()->boolean(90), // 90% peluang aktif
        ];
    }
}
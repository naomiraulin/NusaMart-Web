<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'idProduct' => Str::uuid()->toString(),
            // idStore akan diisi dari seeder
            'productName' => fake()->words(3, true),
            'description' => fake()->paragraph(),
            'weightGram' => fake()->numberBetween(100, 5000), // Berat acak antara 100gr - 5kg
            'productStatus' => fake()->randomElement(['ACTIVE', 'ACTIVE', 'OUT_OF_STOCK']), // Lebih banyak peluang aktif
            'avgRating' => fake()->randomFloat(1, 3, 5), // Rating acak antara 3.0 sampai 5.0
            'sold' => fake()->numberBetween(0, 500), // Jumlah terjual acak 0 - 500
        ];
    }
}
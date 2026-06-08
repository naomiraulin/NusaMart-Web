<?php

namespace Database\Factories;

use App\Models\Product;
use App\Services\IdGeneratorService;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'idProduct'     => app(IdGeneratorService::class)->generate('PRD', Product::class, 'idProduct'),
            'productName'   => fake()->words(3, true),
            'description'   => fake()->paragraph(),
            'weightGram'    => fake()->numberBetween(100, 5000),
            'productStatus' => fake()->randomElement(['ACTIVE', 'ACTIVE', 'OUT_OF_STOCK']),
            'avgRating'     => fake()->randomFloat(1, 3, 5),
            'sold'          => fake()->numberBetween(0, 500),
            'createAt'      => now(),
            'updateAt'      => now(),
        ];
    }
}
<?php

namespace Database\Factories;

use App\Models\ProductItem;
use App\Services\IdGeneratorService;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'idItem'   => app(IdGeneratorService::class)->generate('ITM', ProductItem::class, 'idItem'),
            'sku'      => fake()->unique()->bothify('SKU-####-????'),
            'stock'    => fake()->numberBetween(0, 200),
            'price'    => fake()->numberBetween(10000, 500000),
            'isActive' => fake()->boolean(90),
        ];
    }
}
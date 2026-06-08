<?php

namespace Database\Factories;

use App\Models\ProductImage;
use App\Services\IdGeneratorService;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductImageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'idImage'  => app(IdGeneratorService::class)->generate('IMG', ProductImage::class, 'idImage'),
            'imageURL' => fake()->imageUrl(640, 480, 'products', true),
            'isPrimary' => false,
        ];
    }
}
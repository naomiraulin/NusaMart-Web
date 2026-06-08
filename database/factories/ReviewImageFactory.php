<?php

namespace Database\Factories;

use App\Models\ReviewImage;
use App\Services\IdGeneratorService;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewImageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'idRevImage' => app(IdGeneratorService::class)->generate('RVI', ReviewImage::class, 'idRevImage'),
            'urlImage'   => fake()->imageUrl(640, 480, 'products', true),
        ];
    }
}
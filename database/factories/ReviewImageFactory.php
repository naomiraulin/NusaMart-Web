<?php

namespace Database\Factories;

use App\Models\ReviewImage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ReviewImageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'idRevImage' => Str::uuid()->toString(),
            // idReview akan disuntikkan melalui Seeder
            'urlImage' => fake()->imageUrl(640, 480, 'products', true), // Anggap ini foto produk yang sampai
        ];
    }
}
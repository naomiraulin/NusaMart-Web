<?php

namespace Database\Factories;

use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ReviewFactory extends Factory
{
    public function definition(): array
    {
        // Simulasi rating yang realistis (banyak bintang 4 atau 5, sedikit bintang kecil)
        $rating = fake()->randomElement([3.0, 4.0, 4.5, 5.0, 5.0, 5.0]);
        
        return [
            'idReview' => Str::uuid()->toString(),
            // idOrderItem dan idUser akan disuntikkan via Seeder
            'rating' => $rating,
            'comment' => fake()->paragraph(),
            'isHidden' => fake()->boolean(5), // Hanya 5% kemungkinan ulasan disembunyikan (moderasi)
        ];
    }
}
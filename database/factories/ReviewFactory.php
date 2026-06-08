<?php

namespace Database\Factories;

use App\Models\Review;
use App\Services\IdGeneratorService;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    public function definition(): array
    {
        $rating = fake()->randomElement([3.0, 4.0, 4.5, 5.0, 5.0, 5.0]);

        return [
            'idReview' => app(IdGeneratorService::class)->generate('REV', Review::class, 'idReview'),
            'rating'   => $rating,
            'comment'  => fake()->paragraph(),
            'isHidden' => fake()->boolean(5),
            'createAt' => now(),
        ];
    }
}
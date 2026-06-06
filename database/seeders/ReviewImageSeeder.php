<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\ReviewImage;
use Illuminate\Database\Seeder;

class ReviewImageSeeder extends Seeder
{
    public function run(): void
    {
        $reviews = Review::all();

        if ($reviews->count() > 0) {
            foreach ($reviews as $review) {
                // Simulasikan 40% ulasan memiliki foto
                if (rand(1, 100) <= 40) {
                    // Buat 1 hingga 3 foto untuk ulasan ini
                    ReviewImage::factory(rand(1, 3))->create([
                        'idReview' => $review->idReview,
                    ]);
                }
            }
        }
    }
}
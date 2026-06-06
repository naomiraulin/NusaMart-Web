<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'idCategory' => Str::uuid()->toString(),
            'categoryName' => fake()->unique()->word(), // Nama kategori satu kata acak
            'isActive' => true,
        ];
    }
}
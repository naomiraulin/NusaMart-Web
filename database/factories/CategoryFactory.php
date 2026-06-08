<?php

namespace Database\Factories;

use App\Models\Category;
use App\Services\IdGeneratorService;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'idCategory'   => app(IdGeneratorService::class)->generate('CAT', Category::class, 'idCategory'),
            'categoryName' => fake()->unique()->word(),
            'isActive'     => true,
        ];
    }
}
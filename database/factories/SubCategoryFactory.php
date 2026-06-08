<?php

namespace Database\Factories;

use App\Models\SubCategory;
use App\Services\IdGeneratorService;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubCategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'idSubCategory'   => app(IdGeneratorService::class)->generate('SUB', SubCategory::class, 'idSubCategory'),
            'subCategoryName' => fake()->unique()->words(2, true),
            'description'     => fake()->sentence(),
        ];
    }
}
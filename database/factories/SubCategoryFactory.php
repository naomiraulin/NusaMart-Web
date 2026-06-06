<?php

namespace Database\Factories;

use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SubCategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'idSubCategory' => Str::uuid()->toString(),
            // idCategory akan diisi dari seeder
            'subCategoryName' => fake()->unique()->words(2, true), // 2 kata acak
            'description' => fake()->sentence(),
        ];
    }
}
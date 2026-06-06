<?php

namespace Database\Factories;

use App\Models\ProductSubCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductSubCategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'idProductSubCat' => Str::uuid()->toString(),
            // idProduct dan idSubCategory akan disuntikkan dari Seeder
        ];
    }
}
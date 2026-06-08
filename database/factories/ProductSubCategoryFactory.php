<?php

namespace Database\Factories;

use App\Models\ProductSubCategory;
use App\Services\IdGeneratorService;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductSubCategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'idProductSubCat' => app(IdGeneratorService::class)->generate('PSC', ProductSubCategory::class, 'idProductSubCat'),
        ];
    }
}
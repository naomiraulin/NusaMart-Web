<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SubCategory;
use App\Services\IdGeneratorService;
use Illuminate\Database\Seeder;

class SubCategorySeeder extends Seeder
{
    public function run(): void
    {
        $idGenerator = app(IdGeneratorService::class);
        $categories = Category::all();

        foreach ($categories as $category) {
            for ($i = 0; $i < 3; $i++) {
                SubCategory::create([
                    'idSubCategory'   => $idGenerator->generate('SUB', SubCategory::class, 'idSubCategory'),
                    'idCategory'      => $category->idCategory,
                    'subCategoryName' => fake()->unique()->words(2, true),
                    'description'     => fake()->sentence(),
                ]);
            }
        }
    }
}
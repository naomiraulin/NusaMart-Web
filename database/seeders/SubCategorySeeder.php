<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;

class SubCategorySeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua master data kategori
        $categories = Category::all();

        foreach ($categories as $category) {
            // Buat 3 sub-kategori acak untuk setiap kategori utama
            SubCategory::factory(3)->create([
                'idCategory' => $category->idCategory,
            ]);
        }
    }
}
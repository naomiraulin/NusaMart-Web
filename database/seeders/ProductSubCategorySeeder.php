<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\SubCategory;
use App\Models\ProductSubCategory;
use Illuminate\Database\Seeder;

class ProductSubCategorySeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();
        $subCategories = SubCategory::all();

        // Pastikan ada sub-kategori agar tidak error
        if ($subCategories->count() > 0) {
            foreach ($products as $product) {
                // Berikan setiap produk 1 sampai 2 sub-kategori acak
                $randomSubCats = $subCategories->random(rand(1, min(2, $subCategories->count())));
                
                foreach ($randomSubCats as $subCat) {
                    ProductSubCategory::factory()->create([
                        'idProduct' => $product->idProduct,
                        'idSubCategory' => $subCat->idSubCategory,
                    ]);
                }
            }
        }
    }
}
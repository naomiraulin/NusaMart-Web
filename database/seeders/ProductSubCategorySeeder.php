<?php

namespace Database\Seeders;

use App\Models\ProductSubCategory;
use Illuminate\Database\Seeder;

class ProductSubCategorySeeder extends Seeder
{
    public function run(): void
    {
        $productSubCategories = [
            [
                'idProductSubCat' => 'PSC-000001',
                'idProduct'       => 'PRD-000001',
                'idSubCategory'   => 'SUB-000001',
            ],
            [
                'idProductSubCat' => 'PSC-000002',
                'idProduct'       => 'PRD-000002',
                'idSubCategory'   => 'SUB-000002',
            ],
        ];

        foreach ($productSubCategories as $item) {
            ProductSubCategory::create($item);
        }
    }
}
<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;

class SubCategorySeeder extends Seeder
{
    public function run(): void
    {
        $subCategories = [
            [
                'idSubCategory'   => 'SUB-000001',
                'idCategory'      => 'CAT-000001',
                'subCategoryName' => 'Batik',
                'description'     => 'Batik tulis dan cap lokal',
            ],
            [
                'idSubCategory'   => 'SUB-000002',
                'idCategory'      => 'CAT-000002',
                'subCategoryName' => 'Kopi',
                'description'     => 'Biji kopi asli daerah',
            ],
        ];

        foreach ($subCategories as $sub) {
            SubCategory::create($sub);
        }
    }
}
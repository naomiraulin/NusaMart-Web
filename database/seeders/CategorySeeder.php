<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['id' => 'CAT-000001', 'name' => 'Makanan & Minuman', 'subs' => ['Makanan Ringan', 'Minuman', 'Bumbu & Rempah']],
            ['id' => 'CAT-000002', 'name' => 'Fashion',           'subs' => ['Pakaian Pria', 'Pakaian Wanita', 'Aksesoris']],
            ['id' => 'CAT-000003', 'name' => 'Kerajinan Tangan',  'subs' => ['Anyaman', 'Ukiran', 'Batik']],
            ['id' => 'CAT-000004', 'name' => 'Pertanian',         'subs' => ['Sayuran', 'Buah-buahan', 'Rempah']],
        ];

        $subCounter = 1;

        foreach ($categories as $cat) {
            Category::create([
                'idCategory'   => $cat['id'],
                'categoryName' => $cat['name'],
                'isActive'     => true,
            ]);

            foreach ($cat['subs'] as $subName) {
                SubCategory::create([
                    'idSubCategory'   => 'SUB-' . str_pad($subCounter, 6, '0', STR_PAD_LEFT),
                    'idCategory'      => $cat['id'],
                    'subCategoryName' => $subName,
                    'description'     => null,
                ]);
                $subCounter++;
            }
        }
    }
}
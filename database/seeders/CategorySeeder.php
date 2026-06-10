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
            [
                'id'      => 'CAT-000001',
                'name'    => 'Fashion Lokal',
                'iconURL' => null,
                'subs'    => [
                    ['id' => 'SUB-000001', 'name' => 'Batik', 'description' => 'Batik tulis dan cap lokal'],
                ],
            ],
            [
                'id'      => 'CAT-000002',
                'name'    => 'Kuliner Nusantara',
                'iconURL' => null,
                'subs'    => [
                    ['id' => 'SUB-000002', 'name' => 'Kopi', 'description' => 'Biji kopi asli daerah'],
                ],
            ],
        ];

        foreach ($categories as $cat) {
            Category::create([
                'idCategory'   => $cat['id'],
                'categoryName' => $cat['name'],
                'iconURL'      => $cat['iconURL'],
                'isActive'     => true,
            ]);

            foreach ($cat['subs'] as $sub) {
                SubCategory::create([
                    'idSubCategory'   => $sub['id'],
                    'idCategory'      => $cat['id'],
                    'subCategoryName' => $sub['name'],
                    'description'     => $sub['description'],
                ]);
            }
        }
    }
}
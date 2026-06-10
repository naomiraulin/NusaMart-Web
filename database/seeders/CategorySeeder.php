<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'id'   => 'CAT-000001',
                'name' => 'Fashion Lokal',
            ],
            [
                'id'   => 'CAT-000002',
                'name' => 'Kuliner Nusantara',
            ],
        ];

        foreach ($categories as $cat) {
            Category::create([
                'idCategory'   => $cat['id'],
                'categoryName' => $cat['name'],
                'isActive'     => true,
            ]);
        }
    }
}
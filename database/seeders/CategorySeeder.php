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
            ['id' => 'CAT-000003', 'name' => 'Kerajinan Tangan'],
            ['id' => 'CAT-000004', 'name' => 'Kesehatan & Kecantikan'],
            ['id' => 'CAT-000005', 'name' => 'Agrobisnis & Perikanan'],
            ['id' => 'CAT-000006', 'name' => 'Rumah Tangga & Dekorasi'],
            ['id' => 'CAT-000007', 'name' => 'Kesenian & Alat Musik'],
            ['id' => 'CAT-000008', 'name' => 'Suvenir & Pernikahan'],
            ['id' => 'CAT-000009', 'name' => 'Mainan & Hobi Lokal'],
            ['id' => 'CAT-000010', 'name' => 'Buku & Alat Tulis'],
            ['id' => 'CAT-000011', 'name' => 'Perlengkapan Ibu & Bayi'],
            ['id' => 'CAT-000012', 'name' => 'Perlengkapan Ibadah'],
            ['id' => 'CAT-000013', 'name' => 'Otomotif & Aksesori'],
            ['id' => 'CAT-000014', 'name' => 'Bahan Baku UMKM'],
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
<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Kategori spesifik untuk UMKM
        $umkmCategories = [
            'Kerajinan Tangan',
            'Makanan & Minuman Lokal',
            'Pakaian Tradisional',
            'Kesehatan & Herbal',
            'Perabotan Kayu',
            'Aksesoris Etnik',
            'Bahan Pangan Mentah',
            'Produk Daur Ulang'
        ];

        // Format ID berurutan agar rapi (opsional, bisa pakai UUID jika mau)
        $idCounter = 1;

        foreach ($umkmCategories as $categoryName) {
            Category::create([
                'idCategory' => 'CAT-' . str_pad($idCounter, 3, '0', STR_PAD_LEFT), // Hasil: CAT-001, CAT-002, dll.
                'categoryName' => $categoryName,
                'isActive' => true,
            ]);
            $idCounter++;
        }

        // Jika butuh kategori acak tambahan, bisa aktifkan baris di bawah ini:
        // Category::factory(5)->create();
    }
}
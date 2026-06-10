<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'idProduct'     => 'PRD-000001',
                'idStore'       => 'STR-000001',
                'productName'   => 'Vas Bunga Anyaman Rotan',
                'description'   => 'Vas bunga estetik buat foto-foto hasil kerajinan tangan pengrajin lokal berbakat.',
                'weightGram'    => 500.0,
                'productStatus' => 'ACTIVE',
                'createAt'      => '2026-04-01 10:00:00',
                'updateAt'      => '2026-05-14 20:00:00',
                'avgRating'     => 4.9
            ],
            [
                'idProduct'     => 'PRD-000002',
                'idStore'       => 'STR-000002',
                'productName'   => 'Kemeja Batik Tulis Solo',
                'description'   => 'Batik tulis asli motif parang, bahan katun no polyester, high quality, motif keren tidak tertandingi HAHA',
                'weightGram'    => 300.0,
                'productStatus' => 'ACTIVE',
                'createAt'      => '2026-04-05 11:00:00',
                'updateAt'      => '2026-05-10 09:00:00',
                'avgRating'     => 4.8
            ],
            [
                'idProduct'     => 'PRD-000003',
                'idStore'       => 'STR-000003',
                'productName'   => 'Kopi Arabica pack 250 gram',
                'description'   => 'Bubuk kopi arabica dengan biji kopi pilihan, membuat mata segar dan jadi bersemangat 45',
                'weightGram'    => 250.0,
                'productStatus' => 'ACTIVE',
                'createAt'      => '2026-05-01 08:00:00',
                'updateAt'      => '2026-05-14 08:00:00',
                'avgRating'     => 4.9
            ],
            [
                'idProduct'     => 'PRD-000004',
                'idStore'       => 'STR-000004',
                'productName'   => 'Rak Dinding Kayu Jati',
                'description'   => 'Rak minimalis handmade dengan finishing halus mulus tanpa minus',
                'weightGram'    => 2000.0,
                'productStatus' => 'ACTIVE',
                'createAt'      => '2026-04-20 10:00:00',
                'updateAt'      => '2026-05-12 11:00:00',
                'avgRating'     => 4.7
            ],
            [
                'idProduct'     => 'PRD-000005',
                'idStore'       => 'STR-000005',
                'productName'   => 'Gorden Perca Shabby',
                'description'   => 'Gorden unik dari kain perca motif bunga, membuat yang melihat jadi berbunga-bunga',
                'weightGram'    => 1200.0,
                'productStatus' => 'ACTIVE',
                'createAt'      => '2026-04-25 09:00:00',
                'updateAt'      => '2026-05-11 13:00:00',
                'avgRating'     => 4.8
            ],
            [
                'idProduct'     => 'PRD-000006',
                'idStore'       => 'STR-000006',
                'productName'   => 'Masker Kunyit Herbal',
                'description'   => 'Masker organik untuk mencerahkan kulitmu dan mengangkat segala kusam wajahmu, dijamin habis pakai tidak jadi kuning',
                'weightGram'    => 100.0,
                'productStatus' => 'ACTIVE',
                'createAt'      => '2026-05-05 14:00:00',
                'updateAt'      => '2026-05-14 10:00:00',
                'avgRating'     => 4.6
            ],
            [
                'idProduct'     => 'PRD-000007',
                'idStore'       => 'STR-000007',
                'productName'   => 'Keripik Tempe Sagu Kress',
                'description'   => 'Kripik tempe gurih renyah tanpa pengawet cocok buat teman nonton, scroll tiktok, dan lain-lain',
                'weightGram'    => 200.0,
                'productStatus' => 'ACTIVE',
                'createAt'      => '2026-05-08 08:00:00',
                'updateAt'      => '2026-05-14 09:00:00',
                'avgRating'     => 5.0
            ],
            [
                'idProduct'     => 'PRD-000008',
                'idStore'       => 'STR-000008',
                'productName'   => 'Pelet Lele Super-Growth',
                'description'   => 'Pakan lele protein tinggi untuk pertumbuhan maksimal, sehat, tidak stunting',
                'weightGram'    => 5000.0,
                'productStatus' => 'ACTIVE',
                'createAt'      => '2026-05-10 10:00:00',
                'updateAt'      => '2026-05-14 11:00:00',
                'avgRating'     => 4.7
            ],
            [
                'idProduct'     => 'PRD-000009',
                'idStore'       => 'STR-000009',
                'productName'   => 'Outer Tenun Ikat',
                'description'   => 'Kombinasi fashion modern dengan kain tenun tradisional.',
                'weightGram'    => 600.0,
                'productStatus' => 'ACTIVE',
                'createAt'      => '2026-05-12 15:00:00',
                'updateAt'      => '2026-05-14 15:00:00',
                'avgRating'     => 4.9
            ],
            [
                'idProduct'     => 'PRD-000010',
                'idStore'       => 'STR-000010',
                'productName'   => 'Rendang Daging Frozen',
                'description'   => 'Rendang asli resep Bude, praktis...tinggal panaskan lalu makan, sedap sedappp',
                'weightGram'    => 400.0,
                'productStatus' => 'ACTIVE',
                'createAt'      => '2026-05-13 09:00:00',
                'updateAt'      => '2026-05-14 16:00:00',
                'avgRating'     => 4.8
            ]
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }
    }
}
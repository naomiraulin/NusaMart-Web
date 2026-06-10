<?php

namespace Database\Seeders;

use App\Models\Seller;
use App\Models\Store;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        $sellers = Seller::all()->keyBy('idSeller');

        $storeData = [
            [
                'idStore'     => 'STR-000001',
                'idSeller'    => 'SLR-000001',
                'name'        => 'Kerajinan Nusantara',
                'description' => 'Menjual berbagai kerajinan tangan dan produk UMKM lokal.',
                'location'    => 'Jakarta Barat',
                'storeRating' => 4.8,
                'isActive'    => true,
                'createAt'    => '2026-01-10T08:00:00',
                'updateAt'    => '2026-05-10T10:00:00',
            ],
            [
                'idStore'     => 'STR-000002',
                'idSeller'    => 'SLR-000002',
                'name'        => 'Batik Merona',
                'description' => 'Produk fashion batik kreasi tangan-tangan berkualitas.',
                'location'    => 'Solo',
                'storeRating' => 4.5,
                'isActive'    => true,
                'createAt'    => '2026-01-12T09:30:00',
                'updateAt'    => '2026-04-15T11:00:00',
            ],
            [
                'idStore'     => 'STR-000003',
                'idSeller'    => 'SLR-000003',
                'name'        => 'Kopi Cap Tjay',
                'description' => 'Menjual bubuk kopi aroma wangi yang murni dan berkualitas.',
                'location'    => 'Malang',
                'storeRating' => 4.9,
                'isActive'    => true,
                'createAt'    => '2026-02-01T07:00:00',
                'updateAt'    => '2026-05-01T07:00:00',
            ],
            [
                'idStore'     => 'STR-000004',
                'idSeller'    => 'SLR-000004',
                'name'        => 'Tukang Kayu Joss',
                'description' => 'Ada sesuatu di Jogja, yaitu Kerajinan dekorasi rumah handmade yang indah sekali.',
                'location'    => 'Yogyakarta',
                'storeRating' => 4.7,
                'isActive'    => true,
                'createAt'    => '2026-02-05T10:00:00',
                'updateAt'    => '2026-05-05T12:00:00',
            ],
            [
                'idStore'     => 'STR-000005',
                'idSeller'    => 'SLR-000005',
                'name'        => 'Melly Gorden',
                'description' => 'Gorden dari kain perca lucu imut gemas gelombang kanan kiri seimbang',
                'location'    => 'Surabaya',
                'storeRating' => 4.6,
                'isActive'    => true,
                'createAt'    => '2026-02-10T08:00:00',
                'updateAt'    => '2026-05-08T09:00:00',
            ],
            [
                'idStore'     => 'STR-000006',
                'idSeller'    => 'SLR-000006',
                'name'        => 'Cantik Alami Nusantara',
                'description' => 'Skincare herbal dan produk kecantikan buatan Indonesia.',
                'location'    => 'Jakarta Selatan',
                'storeRating' => 4.4,
                'isActive'    => true,
                'createAt'    => '2026-02-15T11:00:00',
                'updateAt'    => '2026-05-12T14:00:00',
            ],
            [
                'idStore'     => 'STR-000007',
                'idSeller'    => 'SLR-000007',
                'name'        => 'Cemilan Kress',
                'description' => 'Berbagai camilan hasil rumahan yang buat kamu tidak berhenti mengunyah >_<.',
                'location'    => 'Semarang',
                'storeRating' => 4.9,
                'isActive'    => true,
                'createAt'    => '2026-03-01T09:00:00',
                'updateAt'    => '2026-05-01T10:00:00',
            ],
            [
                'idStore'     => 'STR-000008',
                'idSeller'    => 'SLR-000008',
                'name'        => 'Sahabat si Kumis',
                'description' => 'Menjual kebutuhan ternak lele kualitas tinggi no impor asli dari jawa.',
                'location'    => 'Pacitan',
                'storeRating' => 4.7,
                'isActive'    => true,
                'createAt'    => '2026-03-05T10:00:00',
                'updateAt'    => '2026-05-10T11:00:00',
            ],
            [
                'idStore'     => 'STR-000009',
                'idSeller'    => 'SLR-000009',
                'name'        => 'Go Fashion',
                'description' => 'Outfit keren yang kalcer dengan tetap mempertahankan budaya nusantara',
                'location'    => 'Jakarta Pusat',
                'storeRating' => 4.8,
                'isActive'    => true,
                'createAt'    => '2026-03-10T08:30:00',
                'updateAt'    => '2026-05-14T15:00:00',
            ],
            [
                'idStore'     => 'STR-000010',
                'idSeller'    => 'SLR-000010',
                'name'        => 'Dapur Bude',
                'description' => 'Olahan masakan frozen yang dibuat dengan sepenuh hati bude',
                'location'    => 'Sukoharjo',
                'storeRating' => 4.5,
                'isActive'    => true,
                'createAt'    => '2026-03-15T09:00:00',
                'updateAt'    => '2026-05-14T16:00:00',
            ],
        ];

        foreach ($storeData as $data) {
            $seller = $sellers->get($data['idSeller']);

            if (!$seller) {
                continue;
            }

            Store::factory()->create([
                'idStore'     => $data['idStore'],
                'idSeller'    => $seller->idSeller,
                'name'        => $data['name'],
                'description' => $data['description'],
                'location'    => $data['location'],
                'storeRating' => $data['storeRating'],
                'isActive'    => $data['isActive'],
                'createAt'    => $data['createAt'],
                'updateAt'    => $data['updateAt'],
            ]);
        }
    }
}
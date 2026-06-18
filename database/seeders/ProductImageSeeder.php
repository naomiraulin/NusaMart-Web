<?php

namespace Database\Seeders;

use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        $productImages = [
            // PRD-000001 - Vas Bunga Anyaman Rotan
            ['idImage' => 'IMG-000001', 'idProduct' => 'PRD-000001', 'imageURL' => 'storage/products/PRD-000001/aa.jpg', 'isPrimary' => true],
            ['idImage' => 'IMG-000002', 'idProduct' => 'PRD-000001', 'imageURL' => 'storage/products/PRD-000001/bb.jpg', 'isPrimary' => false],

            // PRD-000002 - Kemeja Batik Tulis Solo
            ['idImage' => 'IMG-000003', 'idProduct' => 'PRD-000002', 'imageURL' => 'storage/products/PRD-000002/aa.jpg', 'isPrimary' => true],
            ['idImage' => 'IMG-000004', 'idProduct' => 'PRD-000002', 'imageURL' => 'storage/products/PRD-000002/bb.jpg', 'isPrimary' => false],

            // PRD-000003 - Kopi Arabica pack 250 gram
            ['idImage' => 'IMG-000005', 'idProduct' => 'PRD-000003', 'imageURL' => 'storage/products/PRD-000003/aa.jpg', 'isPrimary' => true],
            ['idImage' => 'IMG-000006', 'idProduct' => 'PRD-000003', 'imageURL' => 'storage/products/PRD-000003/bb.jpg', 'isPrimary' => false],

            // PRD-000004 - Rak Dinding Kayu Jati
            ['idImage' => 'IMG-000007', 'idProduct' => 'PRD-000004', 'imageURL' => 'storage/products/PRD-000004/aa.jpg', 'isPrimary' => true],
            ['idImage' => 'IMG-000008', 'idProduct' => 'PRD-000004', 'imageURL' => 'storage/products/PRD-000004/bb.jpg', 'isPrimary' => false],

            // PRD-000005 - Gorden Perca Shabby
            ['idImage' => 'IMG-000009', 'idProduct' => 'PRD-000005', 'imageURL' => 'storage/products/PRD-000005/aa.jpg', 'isPrimary' => true],
            ['idImage' => 'IMG-000010', 'idProduct' => 'PRD-000005', 'imageURL' => 'storage/products/PRD-000005/bb.jpg', 'isPrimary' => false],

            // PRD-000006 - Masker Kunyit Herbal
            ['idImage' => 'IMG-000011', 'idProduct' => 'PRD-000006', 'imageURL' => 'storage/products/PRD-000006/aa.jpg', 'isPrimary' => true],
            ['idImage' => 'IMG-000012', 'idProduct' => 'PRD-000006', 'imageURL' => 'storage/products/PRD-000006/bb.jpg', 'isPrimary' => false],

            // PRD-000007 - Keripik Tempe Sagu Kress
            ['idImage' => 'IMG-000013', 'idProduct' => 'PRD-000007', 'imageURL' => 'storage/products/PRD-000007/aa.jpg', 'isPrimary' => true],
            ['idImage' => 'IMG-000014', 'idProduct' => 'PRD-000007', 'imageURL' => 'storage/products/PRD-000007/bb.jpg', 'isPrimary' => false],

            // PRD-000008 - Pelet Lele Super-Growth
            ['idImage' => 'IMG-000015', 'idProduct' => 'PRD-000008', 'imageURL' => 'storage/products/PRD-000008/aa.jpg', 'isPrimary' => true],
            ['idImage' => 'IMG-000016', 'idProduct' => 'PRD-000008', 'imageURL' => 'storage/products/PRD-000008/bb.jpg', 'isPrimary' => false],

            // PRD-000009 - Outer Tenun Ikat
            ['idImage' => 'IMG-000017', 'idProduct' => 'PRD-000009', 'imageURL' => 'storage/products/PRD-000009/aa.jpg', 'isPrimary' => true],
            ['idImage' => 'IMG-000018', 'idProduct' => 'PRD-000009', 'imageURL' => 'storage/products/PRD-000009/bb.jpg', 'isPrimary' => false],

            // PRD-000010 - Rendang Daging Frozen
            ['idImage' => 'IMG-000019', 'idProduct' => 'PRD-000010', 'imageURL' => 'storage/products/PRD-000010/aa.jpg', 'isPrimary' => true],
            ['idImage' => 'IMG-000020', 'idProduct' => 'PRD-000010', 'imageURL' => 'storage/products/PRD-000010/bb.jpg', 'isPrimary' => false],
        ];

        foreach ($productImages as $image) {
            ProductImage::create($image);
        }
    }
}
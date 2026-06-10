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
            ['idProduct' => 'PRD-000001', 'imageURL' => 'https://placehold.co/600x400?text=PRD-000001-1', 'isPrimary' => true],
            ['idProduct' => 'PRD-000001', 'imageURL' => 'https://placehold.co/600x400?text=PRD-000001-2', 'isPrimary' => false],

            // PRD-000002 - Kemeja Batik Tulis Solo
            ['idProduct' => 'PRD-000002', 'imageURL' => 'https://placehold.co/600x400?text=PRD-000002-1', 'isPrimary' => true],
            ['idProduct' => 'PRD-000002', 'imageURL' => 'https://placehold.co/600x400?text=PRD-000002-2', 'isPrimary' => false],

            // PRD-000003 - Kopi Arabica pack 250 gram
            ['idProduct' => 'PRD-000003', 'imageURL' => 'https://placehold.co/600x400?text=PRD-000003-1', 'isPrimary' => true],
            ['idProduct' => 'PRD-000003', 'imageURL' => 'https://placehold.co/600x400?text=PRD-000003-2', 'isPrimary' => false],

            // PRD-000004 - Rak Dinding Kayu Jati
            ['idProduct' => 'PRD-000004', 'imageURL' => 'https://placehold.co/600x400?text=PRD-000004-1', 'isPrimary' => true],
            ['idProduct' => 'PRD-000004', 'imageURL' => 'https://placehold.co/600x400?text=PRD-000004-2', 'isPrimary' => false],

            // PRD-000005 - Gorden Perca Shabby
            ['idProduct' => 'PRD-000005', 'imageURL' => 'https://placehold.co/600x400?text=PRD-000005-1', 'isPrimary' => true],
            ['idProduct' => 'PRD-000005', 'imageURL' => 'https://placehold.co/600x400?text=PRD-000005-2', 'isPrimary' => false],

            // PRD-000006 - Masker Kunyit Herbal
            ['idProduct' => 'PRD-000006', 'imageURL' => 'https://placehold.co/600x400?text=PRD-000006-1', 'isPrimary' => true],
            ['idProduct' => 'PRD-000006', 'imageURL' => 'https://placehold.co/600x400?text=PRD-000006-2', 'isPrimary' => false],

            // PRD-000007 - Keripik Tempe Sagu Kress
            ['idProduct' => 'PRD-000007', 'imageURL' => 'https://placehold.co/600x400?text=PRD-000007-1', 'isPrimary' => true],
            ['idProduct' => 'PRD-000007', 'imageURL' => 'https://placehold.co/600x400?text=PRD-000007-2', 'isPrimary' => false],

            // PRD-000008 - Pelet Lele Super-Growth
            ['idProduct' => 'PRD-000008', 'imageURL' => 'https://placehold.co/600x400?text=PRD-000008-1', 'isPrimary' => true],
            ['idProduct' => 'PRD-000008', 'imageURL' => 'https://placehold.co/600x400?text=PRD-000008-2', 'isPrimary' => false],

            // PRD-000009 - Outer Tenun Ikat
            ['idProduct' => 'PRD-000009', 'imageURL' => 'https://placehold.co/600x400?text=PRD-000009-1', 'isPrimary' => true],
            ['idProduct' => 'PRD-000009', 'imageURL' => 'https://placehold.co/600x400?text=PRD-000009-2', 'isPrimary' => false],

            // PRD-000010 - Rendang Daging Frozen
            ['idProduct' => 'PRD-000010', 'imageURL' => 'https://placehold.co/600x400?text=PRD-000010-1', 'isPrimary' => true],
            ['idProduct' => 'PRD-000010', 'imageURL' => 'https://placehold.co/600x400?text=PRD-000010-2', 'isPrimary' => false],
        ];

        foreach ($productImages as $image) {
            ProductImage::create($image);
        }
    }
}
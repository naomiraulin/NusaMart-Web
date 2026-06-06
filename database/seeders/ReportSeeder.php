<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Review;
use App\Models\Report;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $products = Product::all();
        $reviews = Review::all();

        if ($users->count() > 0) {
            // Ambil beberapa user acak sebagai pelapor
            $reporters = $users->random(3);

            foreach ($reporters as $reporter) {
                // 1. Laporan terhadap Produk
                if ($products->count() > 0) {
                    Report::factory()->create([
                        'reporterId' => $reporter->idUser,
                        'type' => 'product',
                        'referenceId' => $products->random()->idProduct,
                        'reason' => 'Produk ini sepertinya barang tiruan/palsu.',
                    ]);
                }

                // 2. Laporan terhadap Review
                if ($reviews->count() > 0) {
                    Report::factory()->create([
                        'reporterId' => $reporter->idUser,
                        'type' => 'review',
                        'referenceId' => $reviews->random()->idReview,
                        'reason' => 'Ulasan mengandung kata-kata yang tidak pantas.',
                    ]);
                }

                // 3. Laporan Umum (Others)
                Report::factory()->create([
                    'reporterId' => $reporter->idUser,
                    'type' => 'others',
                    'referenceId' => null,
                    'reason' => 'Saya menemukan bug saat checkout pesanan.',
                ]);
            }
        }
    }
}
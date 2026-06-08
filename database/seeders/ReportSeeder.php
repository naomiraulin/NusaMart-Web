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
            // Fix: min() agar tidak error kalau user < 3
            $reporters = $users->random(min(3, $users->count()));

            foreach ($reporters as $reporter) {
                if ($products->count() > 0) {
                    Report::factory()->create([
                        'reporterId'  => $reporter->idUser,
                        'type'        => 'product',
                        'referenceId' => $products->random()->idProduct,
                        'reason'      => 'Produk ini sepertinya barang tiruan/palsu.',
                    ]);
                }

                if ($reviews->count() > 0) {
                    Report::factory()->create([
                        'reporterId'  => $reporter->idUser,
                        'type'        => 'review',
                        'referenceId' => $reviews->random()->idReview,
                        'reason'      => 'Ulasan mengandung kata-kata yang tidak pantas.',
                    ]);
                }

                Report::factory()->create([
                    'reporterId'  => $reporter->idUser,
                    'type'        => 'others',
                    'referenceId' => null,
                    'reason'      => 'Saya menemukan bug saat checkout pesanan.',
                ]);
            }
        }
    }
}
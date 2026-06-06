<?php

namespace Database\Seeders;

use App\Models\Seller;
use App\Models\Store;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua seller dari database
        $sellers = Seller::all();

        // Beberapa nama toko manual agar realistis
        $manualStores = [
            'Kerajinan Nusantara',
            'Batik Merona',
            'Cemilan Kress',
            'Go Fashion',
            'Dapur Bude'
        ];

        $manualIndex = 0;

        foreach ($sellers as $seller) {
            // Jika masih ada sisa nama manual, pakai nama manual. Jika habis, pakai factory murni.
            if ($manualIndex < count($manualStores)) {
                Store::factory()->create([
                    'idSeller' => $seller->idSeller,
                    'name' => $manualStores[$manualIndex],
                    'idStore' => 'STR-00000' . ($manualIndex + 1), // Optional jika ingin ID berurutan untuk testing
                ]);
                $manualIndex++;
            } else {
                Store::factory()->create([
                    'idSeller' => $seller->idSeller,
                ]);
            }
        }
    }
}
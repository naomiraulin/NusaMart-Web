<?php

namespace Database\Seeders;

use App\Models\Seller;
use App\Models\Store;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        $sellers = Seller::all();

        $manualStores = [
            'Kerajinan Nusantara',
            'Batik Merona',
            'Cemilan Kress',
            'Go Fashion',
            'Dapur Bude',
        ];

        $manualIndex = 0;
        $counter = 1;

        foreach ($sellers as $seller) {
            if ($manualIndex < count($manualStores)) {
                Store::factory()->create([
                    'idStore'  => 'STR-' . str_pad($counter, 6, '0', STR_PAD_LEFT),
                    'idSeller' => $seller->idSeller,
                    'name'     => $manualStores[$manualIndex],
                ]);
                $manualIndex++;
            } else {
                Store::factory()->create([
                    'idStore'  => 'STR-' . str_pad($counter, 6, '0', STR_PAD_LEFT),
                    'idSeller' => $seller->idSeller,
                ]);
            }
            $counter++;
        }
    }
}
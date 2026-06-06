<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\StoreWallet;
use Illuminate\Database\Seeder;

class StoreWalletSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua data toko
        $stores = Store::all();

        foreach ($stores as $store) {
            StoreWallet::factory()->create([
                'idStore' => $store->idStore,
            ]);
        }
    }
}
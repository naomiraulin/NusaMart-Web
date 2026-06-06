<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\BadgeVerification;
use Illuminate\Database\Seeder;

class BadgeVerificationSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua toko
        $stores = Store::all();

        foreach ($stores as $store) {
            // Beri simulasi 70% toko sudah mengajukan verifikasi badge
            if (rand(1, 100) <= 70) {
                BadgeVerification::factory()->create([
                    'idStore' => $store->idStore,
                ]);
            }
        }
    }
}
<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Seller;
use Illuminate\Database\Seeder;

class SellerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua user yang memiliki role SELLER dari database
        $sellerUsers = User::where('role', 'SELLER')->get();

        foreach ($sellerUsers as $user) {
            // Buat data seller dengan menyamakan idSeller dengan idUser
            Seller::factory()->create([
                'idSeller' => $user->idUser,
            ]);
        }
    }
}
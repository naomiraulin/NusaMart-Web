<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Database\Seeder;

class UserAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua user yang sudah ada
        $users = User::all();

        foreach ($users as $user) {
            // 1. Buatkan 1 alamat utama (isDefault = true) untuk setiap user
            UserAddress::factory()->create([
                'idUser' => $user->idUser,
                'isDefault' => true,
                'label' => 'Alamat Utama',
            ]);

            // 2. Acak (50% probabilitas), berikan user ini alamat kedua (opsional)
            if (rand(0, 1) === 1) {
                UserAddress::factory()->create([
                    'idUser' => $user->idUser,
                    'isDefault' => false,
                    'label' => 'Alamat Cadangan',
                ]);
            }
        }
    }
}
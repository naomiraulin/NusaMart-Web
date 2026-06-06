<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Akun Admin Utama
        User::create([
            'idUser' => Str::uuid()->toString(),
            'username' => 'admin_super',
            'email' => 'admin@nusamart.com',
            'password' => bcrypt('password123'),
            'phone' => '081111111111',
            'role' => 'ADMIN',
            'imageURL' => 'https://via.placeholder.com/150',
        ]);

        // 2. Akun UMKM / Seller
        User::create([
            'idUser' => Str::uuid()->toString(),
            'username' => 'toko_sejahtera',
            'email' => 'toko@umkm.com',
            'password' => bcrypt('password123'),
            'phone' => '082222222222',
            'role' => 'SELLER',
            'imageURL' => 'https://via.placeholder.com/150',
        ]);

        // 3. Buat 10 akun dummy tambahan secara acak via Factory
        User::factory(10)->create();
    }
}
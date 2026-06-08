<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $userService = app(UserService::class);

        // 1. Akun Admin
        User::create([
            'idUser'   => $userService->generateUserId('ADMIN'),
            'username' => 'admin_super',
            'email'    => 'admin@nusamart.com',
            'password' => bcrypt('password123'),
            'phone'    => '081111111111',
            'role'     => 'ADMIN',
            'imageURL' => null,
            'createAt' => now(),
            'updateAt' => now(),
        ]);

        // 2. Akun Seller
        User::create([
            'idUser'   => $userService->generateUserId('SELLER'),
            'username' => 'toko_sejahtera',
            'email'    => 'toko@umkm.com',
            'password' => bcrypt('password123'),
            'phone'    => '082222222222',
            'role'     => 'SELLER',
            'imageURL' => null,
            'createAt' => now(),
            'updateAt' => now(),
        ]);

        // 3. Akun Buyer
        User::create([
            'idUser'   => $userService->generateUserId('BUYER'),
            'username' => 'buyer_pertama',
            'email'    => 'buyer@nusamart.com',
            'password' => bcrypt('password123'),
            'phone'    => '083333333333',
            'role'    => 'BUYER',
            'imageURL' => null,
            'createAt' => now(),
            'updateAt' => now(),
        ]);

        // 4. Dummy tambahan — satu per satu agar ID tidak duplicate
        $dummyUsers = [
            ['role' => 'BUYER',  'username' => 'buyer_dummy1',  'email' => 'buyer1@dummy.com'],
            ['role' => 'BUYER',  'username' => 'buyer_dummy2',  'email' => 'buyer2@dummy.com'],
            ['role' => 'BUYER',  'username' => 'buyer_dummy3',  'email' => 'buyer3@dummy.com'],
            ['role' => 'BUYER',  'username' => 'buyer_dummy4',  'email' => 'buyer4@dummy.com'],
            ['role' => 'BUYER',  'username' => 'buyer_dummy5',  'email' => 'buyer5@dummy.com'],
            ['role' => 'SELLER', 'username' => 'seller_dummy1', 'email' => 'seller1@dummy.com'],
            ['role' => 'SELLER', 'username' => 'seller_dummy2', 'email' => 'seller2@dummy.com'],
            ['role' => 'SELLER', 'username' => 'seller_dummy3', 'email' => 'seller3@dummy.com'],
            ['role' => 'BUYER',  'username' => 'buyer_dummy6',  'email' => 'buyer6@dummy.com'],
            ['role' => 'BUYER',  'username' => 'buyer_dummy7',  'email' => 'buyer7@dummy.com'],
        ];

        foreach ($dummyUsers as $dummy) {
            User::create([
                'idUser'   => $userService->generateUserId($dummy['role']),
                'username' => $dummy['username'],
                'email'    => $dummy['email'],
                'password' => bcrypt('password123'),
                'phone'    => '08' . rand(100000000, 999999999),
                'role'     => $dummy['role'],
                'imageURL' => null,
                'createAt' => now(),
                'updateAt' => now(),
            ]);
        }
    }
}
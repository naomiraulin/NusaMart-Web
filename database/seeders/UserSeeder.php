<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            // --- Akun Admin Tambahan ---
            [
                'idUser'   => 'ADM-000001',
                'username' => 'admin_super',
                'email'    => 'admin@nusamart.com',
                'password' => bcrypt('123'),
                'phone'    => '081111111111',
                'role'     => 'ADMIN',
                'createAt' => '2026-01-01 08:00:00',
                'updateAt' => '2026-05-14 10:00:00',
                'imageURL' => null
            ],
            [
                'idUser'   => 'BYR-000001',
                'username' => 'trisa',
                'email'    => 'trisa@student.uns.ac.id',
                'password' => bcrypt('123'),
                'phone'    => '081234567890',
                'role'     => 'BUYER',
                'createAt' => '2026-05-14 10:00:00',
                'updateAt' => '2026-05-14 10:00:00',
                'imageURL' => null
            ],
            [
                'idUser'   => 'SLR-000001',
                'username' => 'rifqia',
                'email'    => 'rifqia@gmail.com',
                'password' => bcrypt('123'),
                'phone'    => '089876543210',
                'role'     => 'SELLER',
                'createAt' => '2026-01-10 08:00:00',
                'updateAt' => '2026-05-14 10:00:00',
                'imageURL' => null
            ],
            [
                'idUser'   => 'SLR-000002',
                'username' => 'siti_merona',
                'email'    => 'sitiyati@gmail.com',
                'password' => bcrypt('123'),
                'phone'    => '081234567891',
                'role'     => 'SELLER',
                'createAt' => '2026-01-12 09:30:00',
                'updateAt' => '2026-05-14 10:00:00',
                'imageURL' => null
            ],
            [
                'idUser'   => 'SLR-000003',
                'username' => 'budipekerti',
                'email'    => 'budi@gmail.com',
                'password' => bcrypt('123'),
                'phone'    => '081234567892',
                'role'     => 'SELLER',
                'createAt' => '2026-02-01 07:00:00',
                'updateAt' => '2026-05-14 10:00:00',
                'imageURL' => null
            ],
            [
                'idUser'   => 'SLR-000004',
                'username' => 'agus_joss',
                'email'    => 'agus123@gmail.com',
                'password' => bcrypt('123'),
                'phone'    => '081234567893',
                'role'     => 'SELLER',
                'createAt' => '2026-02-05 10:00:00',
                'updateAt' => '2026-05-14 10:00:00',
                'imageURL' => null
            ],
            [
                'idUser'   => 'SLR-000005',
                'username' => 'melly_admin',
                'email'    => 'melly@gmail.com',
                'password' => bcrypt('123'),
                'phone'    => '081234567894',
                'role'     => 'SELLER',
                'createAt' => '2026-02-10 08:00:00',
                'updateAt' => '2026-05-14 10:00:00',
                'imageURL' => null
            ],
            [
                'idUser'   => 'SLR-000006',
                'username' => 'dewidewi',
                'email'    => 'dewi@gmail.com',
                'password' => bcrypt('123'),
                'phone'    => '081234567895',
                'role'     => 'SELLER',
                'createAt' => '2026-02-15 11:00:00',
                'updateAt' => '2026-05-14 10:00:00',
                'imageURL' => null
            ],
            [
                'idUser'   => 'SLR-000007',
                'username' => 'ekocemalcemil',
                'email'    => 'ekoyanto@gmail.com',
                'password' => bcrypt('123'),
                'phone'    => '081234567896',
                'role'     => 'SELLER',
                'createAt' => '2026-03-01 09:00:00',
                'updateAt' => '2026-05-14 10:00:00',
                'imageURL' => null
            ],
            [
                'idUser'   => 'SLR-000008',
                'username' => 'jokopakanlele',
                'email'    => 'jokolele@gmail.com',
                'password' => bcrypt('123'),
                'phone'    => '081234567897',
                'role'     => 'SELLER',
                'createAt' => '2026-03-05 10:00:00',
                'updateAt' => '2026-05-14 10:00:00',
                'imageURL' => null
            ],
            [
                'idUser'   => 'SLR-000009',
                'username' => 'andi_gofashion',
                'email'    => 'andifashion@gmail.com',
                'password' => bcrypt('123'),
                'phone'    => '081234567898',
                'role'     => 'SELLER',
                'createAt' => '2026-03-10 08:30:00',
                'updateAt' => '2026-05-14 15:00:00',
                'imageURL' => null
            ],
            [
                'idUser'   => 'SLR-000010',
                'username' => 'bude_mar',
                'email'    => 'maryati01@gmail.com',
                'password' => bcrypt('123'),
                'phone'    => '081234567899',
                'role'     => 'SELLER',
                'createAt' => '2026-03-15 09:00:00',
                'updateAt' => '2026-05-14 16:00:00',
                'imageURL' => null
            ]
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }
    }
}

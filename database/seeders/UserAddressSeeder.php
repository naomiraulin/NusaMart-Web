<?php

namespace Database\Seeders;

use App\Models\UserAddress;
use Illuminate\Database\Seeder;

class UserAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $addresses = [
            [
                'idAddress'       => 'ADR-000001',
                'idUser'          => 'BYR-000001',
                'label'           => 'Kos/Asrama',
                'receiver'        => 'Trisa',
                'phone'           => '081234567890',
                'completeAddress' => 'Kentingan, Jebres, sekitar area UNS',
                'city'            => 'Surakarta',
                'province'        => 'Jawa Tengah',
                'postalCode'      => '57126',
                'isDefault'       => true
            ]
        ];

        foreach ($addresses as $addressData) {
            UserAddress::create($addressData);
        }
    }
}

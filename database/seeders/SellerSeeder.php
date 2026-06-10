<?php

namespace Database\Seeders;

use App\Models\Seller;
use Illuminate\Database\Seeder;

class SellerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sellers = [
            [
                'idSeller'      => 'SLR-000001',
                'nik'           => '3372000000000001',
                'bankName'      => 'BCA',
                'accountNumber' => '1234567890'
            ],
            [
                'idSeller'      => 'SLR-000002',
                'nik'           => '3372000000000002',
                'bankName'      => 'Mandiri',
                'accountNumber' => '9876543210'
            ],
            [
                'idSeller'      => 'SLR-000003',
                'nik'           => '3372000000000003',
                'bankName'      => 'BNI',
                'accountNumber' => '5566778899'
            ],
            [
                'idSeller'      => 'SLR-000004',
                'nik'           => '3372000000000004',
                'bankName'      => 'BRI',
                'accountNumber' => '1122334455'
            ],
            [
                'idSeller'      => 'SLR-000005',
                'nik'           => '3372000000000005',
                'bankName'      => 'BCA',
                'accountNumber' => '2233445566'
            ],
            [
                'idSeller'      => 'SLR-000006',
                'nik'           => '3372000000000006',
                'bankName'      => 'BSI',
                'accountNumber' => '7788990011'
            ],
            [
                'idSeller'      => 'SLR-000007',
                'nik'           => '3372000000000007',
                'bankName'      => 'Mandiri',
                'accountNumber' => '8899001122'
            ],
            [
                'idSeller'      => 'SLR-000008',
                'nik'           => '3372000000000008',
                'bankName'      => 'BNI',
                'accountNumber' => '3344556677'
            ],
            [
                'idSeller'      => 'SLR-000009',
                'nik'           => '3372000000000009',
                'bankName'      => 'BCA',
                'accountNumber' => '4455667788'
            ],
            [
                'idSeller'      => 'SLR-000010',
                'nik'           => '3372000000000010',
                'bankName'      => 'BRI',
                'accountNumber' => '6677889900'
            ]
        ];

        foreach ($sellers as $sellerData) {
            Seller::create($sellerData);
        }
    }
}

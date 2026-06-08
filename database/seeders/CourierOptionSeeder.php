<?php

namespace Database\Seeders;

use App\Models\CourierOption;
use Illuminate\Database\Seeder;

class CourierOptionSeeder extends Seeder
{
    public function run(): void
    {
        $couriers = [
            [
                'idCourier'      => 'CUR-000001',
                'courierName'    => 'JNE Express',
                'serviceType'    => 'REGULER',
                'timeEstimation' => 3,
                'isActive'       => true,
            ],
            [
                'idCourier'      => 'CUR-000002',
                'courierName'    => 'JNE Trucking (JTR)',
                'serviceType'    => 'KARGO',
                'timeEstimation' => 7,
                'isActive'       => true,
            ],
            [
                'idCourier'      => 'CUR-000003',
                'courierName'    => 'J&T EZ',
                'serviceType'    => 'REGULER',
                'timeEstimation' => 2,
                'isActive'       => true,
            ],
            [
                'idCourier'      => 'CUR-000004',
                'courierName'    => 'SiCepat HALU',
                'serviceType'    => 'REGULER',
                'timeEstimation' => 4,
                'isActive'       => true,
            ],
            [
                'idCourier'      => 'CUR-000005',
                'courierName'    => 'SiCepat GOKIL',
                'serviceType'    => 'KARGO',
                'timeEstimation' => 5,
                'isActive'       => true,
            ],
        ];

        foreach ($couriers as $courier) {
            CourierOption::create($courier);
        }
    }
}
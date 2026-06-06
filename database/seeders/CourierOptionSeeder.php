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
                'idCourier' => 'CUR-JNE-REG',
                'courierName' => 'JNE Express',
                'serviceType' => 'REGULER',
                'timeEstimation' => 3, // Estimasi 3 Hari
                'isActive' => true,
            ],
            [
                'idCourier' => 'CUR-JNE-TRC',
                'courierName' => 'JNE Trucking (JTR)',
                'serviceType' => 'KARGO',
                'timeEstimation' => 7,
                'isActive' => true,
            ],
            [
                'idCourier' => 'CUR-JNT-EZ',
                'courierName' => 'J&T EZ',
                'serviceType' => 'REGULER',
                'timeEstimation' => 2,
                'isActive' => true,
            ],
            [
                'idCourier' => 'CUR-SICEPAT-HALU',
                'courierName' => 'SiCepat HALU',
                'serviceType' => 'REGULER',
                'timeEstimation' => 4,
                'isActive' => true,
            ],
            [
                'idCourier' => 'CUR-SICEPAT-GOKIL',
                'courierName' => 'SiCepat GOKIL',
                'serviceType' => 'KARGO',
                'timeEstimation' => 5,
                'isActive' => true,
            ]
        ];

        foreach ($couriers as $courier) {
            CourierOption::create($courier);
        }
    }
}
<?php

namespace Database\Seeders;

use App\Models\CourierOption;
use Illuminate\Database\Seeder;

class CourierOptionSeeder extends Seeder
{
    public function run(): void
    {
        $jsonString = '[
          {
            "idCourier": "CUR-000001",
            "courierName": "JNE Reguler",
            "serviceType": "REGULAR",
            "timeEstimation": 3,
            "isActive": true
          },
          {
            "idCourier": "CUR-000002",
            "courierName": "SiCepat HALU",
            "serviceType": "REGULAR",
            "timeEstimation": 2,
            "isActive": true
          },
          {
            "idCourier": "CUR-000003",
            "courierName": "J&T Cargo",
            "serviceType": "KARGO",
            "timeEstimation": 7,
            "isActive": true
          }
        ]';

        $couriers = json_decode($jsonString, true);

        foreach ($couriers as $courier) {
            CourierOption::create([
                'idCourier'      => $courier['idCourier'],
                'courierName'    => $courier['courierName'],
                'serviceType'    => $courier['serviceType'],
                'timeEstimation' => $courier['timeEstimation'],
                'isActive'       => $courier['isActive'],
            ]);
        }
    }
}
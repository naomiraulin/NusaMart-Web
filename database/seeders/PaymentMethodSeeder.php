<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $jsonString = '[
          {
            "idMethod": "MET-000001",
            "methodName": "QRIS NusaMart",
            "provider": "MIDTRANS",
            "isActive": true
          },
          {
            "idMethod": "MET-000002",
            "methodName": "Transfer Bank BCA",
            "provider": "MANUAL",
            "isActive": true
          },
          {
            "idMethod": "MET-000003",
            "methodName": "Transfer Bank BRI",
            "provider": "MANUAL",
            "isActive": true
          },
          {
            "idMethod": "MET-000004",
            "methodName": "Transfer Bank BNI",
            "provider": "MANUAL",
            "isActive": true
          },
          {
            "idMethod": "MET-000005",
            "methodName": "Transfer Bank MANDIRI",
            "provider": "MANUAL",
            "isActive": true
          },
          {
            "idMethod": "MET-000006",
            "methodName": "Bayar di Tempat (COD)",
            "provider": "COD",
            "isActive": true
          }
        ]';

        $methods = json_decode($jsonString, true);

        foreach ($methods as $method) {
            PaymentMethod::create([
                'idMethod'   => $method['idMethod'],
                'methodName' => $method['methodName'],
                'provider'   => $method['provider'],
                'isActive'   => $method['isActive'],
            ]);
        }
    }
}
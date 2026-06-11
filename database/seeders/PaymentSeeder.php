<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Payment;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Data JSON Spesifik untuk 2 pembayaran (sinkron dengan OrderSeeder)
        $jsonString = '[
          {
            "idPayment": "PAY-000001",
            "idMethod": "MET-000001",
            "transactionIdGateway": "TRX-857392",
            "snapToken": null,
            "paymentStatus": "APPROVED",
            "paymentTime": "2026-05-14T10:05:00",
            "idUser": "BYR-000001",
            "totalAmount": 164500.0,
            "createAt": "2026-05-14T10:00:00",
            "updateAt": "2026-05-14T10:05:00"
          },
          {
            "idPayment": "PAY-000002",
            "idMethod": "MET-000002",
            "transactionIdGateway": "TRX-482019",
            "snapToken": null,
            "paymentStatus": "APPROVED",
            "paymentTime": "2026-05-15T09:10:00",
            "idUser": "BYR-000001",
            "totalAmount": 132500.0,
            "createAt": "2026-05-15T09:00:00",
            "updateAt": "2026-05-15T09:10:00"
          }
        ]';

        $payments = json_decode($jsonString, true);

        // 2. Alur validasi dan eksekusi
        foreach ($payments as $data) {
            // Validasi: Pastikan Order dan Payment Method benar-benar ada
            $methodExists = PaymentMethod::where('idMethod', $data['idMethod'])->exists();

            if ($methodExists) {
                $payment = Payment::create([
                    'idPayment'            => $data['idPayment'],
                    'idMethod'             => $data['idMethod'],
                    'transactionIdGateway' => $data['transactionIdGateway'],
                    'snapToken'            => $data['snapToken'],
                    'paymentStatus'        => $data['paymentStatus'],
                    'paymentTime'          => $data['paymentTime'],
                    'idUser'               => $data['idUser'],
                    'totalAmount'          => (float) $data['totalAmount'],
                    'createAt'             => $data['createAt'],
                    'updateAt'             => $data['updateAt'],
                ]);

                // Update Order yang sesuai dengan idPayment baru
                Order::where('idOrder', $data['idOrder'] ?? null)->update([
                    'idPayment' => $payment->idPayment
                ]);
            }
        }
    }
}
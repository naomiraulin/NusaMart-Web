<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Store;
use App\Models\UserAddress;
use App\Models\Order;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Data JSON dengan tambahan 1 order berstatus DELIVERED
        $jsonString = '[
          {
            "idOrder": "ORD-000001",
            "idPayment": "PAY-000001",
            "idUser": "BYR-000001",
            "idStore": "STR-000002",
            "idAddress": "ADR-000001",
            "invoiceNumber": "INV/20260514/ORD-000001",
            "orderDate": "2026-05-14T10:00:00",
            "arrivedDate": "2026-05-14T13:30:00",
            "orderStatus": "DELIVERED",
            "productTotalPrice": 150000.0,
            "shippingCost": 12000.0,
            "servicePrice": 2500.0,
            "grandTotal": 164500.0,
            "buyerNote": "Tolong dibungkus yang rapi ya kak.",
            "createAt": "2026-05-14T10:00:00",
            "updateAt": "2026-05-14T13:30:00"
          },
          {
            "idOrder": "ORD-000002",
            "idPayment": "PAY-000002",
            "idUser": "BYR-000001",
            "idStore": "STR-000001",
            "idAddress": "ADR-000001",
            "invoiceNumber": "INV/20260515/ORD-000002",
            "orderDate": "2026-05-15T09:00:00",
            "arrivedDate": "2026-05-17T11:00:00",
            "orderStatus": "DELIVERED",
            "productTotalPrice": 115000.0,
            "shippingCost": 15000.0,
            "servicePrice": 2500.0,
            "grandTotal": 164500.0,
            "buyerNote": "Pastikan barangnya aman ya.",
            "createAt": "2026-05-15T09:00:00",
            "updateAt": "2026-05-17T11:00:00"
          }
        ]';

        $orders = json_decode($jsonString, true);

        // 2. Alur validasi dan eksekusi
        foreach ($orders as $data) {
            // Cek apakah User, Store, dan Alamat benar-benar ada di database
            $userExists = User::where('idUser', $data['idUser'])->exists();
            $storeExists = Store::where('idStore', $data['idStore'])->exists();
            $addressExists = UserAddress::where('idAddress', $data['idAddress'])->exists();

            // Jika ketiga relasi tersebut valid, maka buat pesanannya
            if ($userExists && $storeExists && $addressExists) {
                Order::create([
                    'idOrder'           => $data['idOrder'],
                    
                    // CATATAN: Jika pada error sebelumnya kamu memutuskan untuk menghapus
                    // kolom idPayment dari tabel orders, silakan beri komentar (//) pada baris di bawah ini.
                    'idPayment'         => $data['idPayment'], 
                    
                    'idUser'            => $data['idUser'],
                    'idStore'           => $data['idStore'],
                    'idAddress'         => $data['idAddress'],
                    'invoiceNumber'     => $data['invoiceNumber'],
                    'orderDate'         => $data['orderDate'],
                    'arrivedDate'       => $data['arrivedDate'],
                    'orderStatus'       => $data['orderStatus'],
                    
                    // Pastikan harga tersimpan dengan format desimal (float)
                    'productTotalPrice' => (float) $data['productTotalPrice'],
                    'shippingCost'      => (float) $data['shippingCost'],
                    'servicePrice'      => (float) $data['servicePrice'],
                    'grandTotal'        => (float) $data['grandTotal'],
                    
                    'buyerNote'         => $data['buyerNote'],
                    'createAt'          => $data['createAt'],
                    'updateAt'          => $data['updateAt'],
                ]);
            } else {
                // Opsional: Untuk mendeteksi jika data gagal dimasukkan
                // echo "Gagal menambah Order: User, Store, atau Address tidak ditemukan.\n";
            }
        }
    }
}
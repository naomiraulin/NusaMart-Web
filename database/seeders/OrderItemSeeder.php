<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\ProductItem;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;

class OrderItemSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Data JSON spesifik, sinkron dengan OrderSeeder dan skenario Bawang Merah
        $jsonString = '[
          {
            "idOrderItem": "OIT-000001",
            "idOrder": "ORD-000001",
            "idItem": "ITM-000001",
            "quantity": 1,
            "nameSnapshot": "Kemeja Batik Tulis Solo (M)",
            "priceSnapshot": 115000.0
          },
          {
            "idOrderItem": "OIT-000002",
            "idOrder": "ORD-000002",
            "idItem": "ITM-000002",
            "quantity": 1,
            "nameSnapshot": "Kemeja Batik Tulis Solo (L)",
            "priceSnapshot": 115000.0
          }
        ]';

        $orderItems = json_decode($jsonString, true);

        // 2. Alur validasi dan eksekusi
        foreach ($orderItems as $data) {
            // Pastikan Order utama dan Product Item-nya benar-benar ada di database
            $orderExists = Order::where('idOrder', $data['idOrder'])->exists();
            $productItemExists = ProductItem::where('idItem', $data['idItem'])->exists();

            // Jika relasinya valid, masukkan ke database
            if ($orderExists && $productItemExists) {
                OrderItem::create([
                    'idOrderItem'   => $data['idOrderItem'],
                    'idOrder'       => $data['idOrder'],
                    'idItem'        => $data['idItem'],
                    'quantity'      => $data['quantity'],
                    'nameSnapshot'  => $data['nameSnapshot'],
                    
                    // Casting ke float untuk memastikan data tersimpan sebagai desimal
                    'priceSnapshot' => (float) $data['priceSnapshot'],
                ]);
            } else {
                // Opsional: Untuk mendeteksi jika data dilewati karena relasi tidak lengkap
                // echo "Gagal menambah Order Item ({$data['idOrderItem']}): Order atau Produk tidak ditemukan.\n";
            }
        }
    }
}
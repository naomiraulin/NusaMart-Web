<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\CourierOption;
use App\Models\Shipping;
use Illuminate\Database\Seeder;

class ShippingSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil data dari JSON
        $jsonString = '[
          {
            "idShipping": "SHP-000001",
            "idOrder": "ORD-000001",
            "idCourier": "CUR-000001",
            "resi": "JNE1234567890",
            "shippingDate": "2026-05-14T15:00:00",
            "deliveredDate": null,
            "shippingStatus": "IN_TRANSIT"
          },
          {
            "idShipping": "SHP-000002",
            "idOrder": "ORD-000002",
            "idCourier": "CUR-000002",
            "resi": "JNE1234567891",
            "shippingDate": "2026-05-17T11:00:00",
            "deliveredDate": null,
            "shippingStatus": "IN_TRANSIT"
          }
        ]';

        $shippings = json_decode($jsonString, true);

        // 2. Alur validasi dan eksekusi
        foreach ($shippings as $data) {
            // Cek dan ambil data Order dan Courier yang sesuai
            $order = Order::where('idOrder', $data['idOrder'])->first();
            $courierExists = CourierOption::where('idCourier', $data['idCourier'])->exists();

            // Jika Order dan Kurir benar-benar ada di database, eksekusi pembuatannya
            if ($order && $courierExists) {
                Shipping::create([
                    'idShipping'     => $data['idShipping'],
                    'idOrder'        => $data['idOrder'],
                    'idCourier'      => $data['idCourier'],
                    
                    // Ambil harga ongkir langsung dari pesanan agar sinkron dan tidak ada selisih harga
                    'shippingPrice'  => (float) $order->shippingCost, 
                    
                    'resi'           => $data['resi'],
                    'shippingDate'   => $data['shippingDate'],
                    'deliveredDate'  => $data['deliveredDate'],
                    'shippingStatus' => $data['shippingStatus'],
                ]);
            } else {
                // Opsional: Untuk melihat jika ada data pengiriman yang dilewati karena relasinya tidak lengkap
                // echo "Gagal menambah SHP: Order atau Courier tidak ditemukan.\n";
            }
        }
    }
}
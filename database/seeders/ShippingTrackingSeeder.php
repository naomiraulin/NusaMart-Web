<?php

namespace Database\Seeders;

use App\Models\Shipping;
use App\Models\ShippingTracking;
use Illuminate\Database\Seeder;

class ShippingTrackingSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil data dari JSON
        $jsonString = '[
          {
            "idTracking": "TRK-000001",
            "idShipping": "SHP-000001",
            "packetLocation": "Surakarta",
            "description": "Paket telah diserahkan ke pihak ekspedisi JNE",
            "updateAt": "2026-05-14T15:00:00"
          }
        ]';

        $trackings = json_decode($jsonString, true);

        // 2. Alur validasi dan eksekusi
        foreach ($trackings as $data) {
            // Cek apakah idShipping (referensi pengirimannya) benar-benar ada di tabel shippings
            $shippingExists = Shipping::where('idShipping', $data['idShipping'])->exists();

            // Jika pengiriman valid, masukkan data pelacakan
            if ($shippingExists) {
                ShippingTracking::create([
                    'idTracking'     => $data['idTracking'],
                    'idShipping'     => $data['idShipping'],
                    'packetLocation' => $data['packetLocation'],
                    'description'    => $data['description'],
                    
                    // Catatan: Jika databasemu menggunakan camelCase
                    'updateAt'       => $data['updateAt'],
                    'createAt'       => $data['updateAt'], // Biasanya waktu awal dibuat sama dengan waktu update
                ]);
            } else {
                // Opsional: Untuk mendeteksi jika ada relasi yang tidak ketemu
                // echo "Gagal menambah tracking: Pengiriman dengan ID {$data['idShipping']} tidak ditemukan.\n";
            }
        }
    }
}
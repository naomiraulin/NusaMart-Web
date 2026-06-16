<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\BadgeVerification;
use Illuminate\Database\Seeder;

class BadgeVerificationSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Data JSON Spesifik untuk pengujian badge UMKM
        $jsonString = '[
          {
            "idBadge": "BDG-000001",
            "idStore": "STR-000001",
            "badgeType": "LOCAL",
            "requestDate": "2026-05-14T10:00:00",
            "reviewDate": "2026-05-15T10:00:00",
            "endDate": "2027-05-15T10:00:00",
            "status": "APPROVED",
            "notes": "Telah diverifikasi sebagai produk asli UMKM lokal."
          }
        ]';

        $badges = json_decode($jsonString, true);

        // 2. Alur validasi dan eksekusi
        foreach ($badges as $data) {
            // Validasi: Pastikan toko yang mengajukan badge benar-benar ada di database
            $storeExists = Store::where('idStore', $data['idStore'])->exists();

            // Eksekusi jika relasi toko valid
            if ($storeExists) {
                BadgeVerification::create([
                    'idBadge'     => $data['idBadge'],
                    'idStore'     => $data['idStore'],
                    'badgeType'   => $data['badgeType'],
                    'requestDate' => $data['requestDate'],
                    'reviewDate'  => $data['reviewDate'],
                    'endDate'     => $data['endDate'],
                    'status'      => $data['status'],
                    'notes'       => $data['notes'],
                ]);
            } else {
                // Opsional: Logika jika data gagal dimasukkan karena toko tidak ditemukan
                // echo "Gagal menambah Badge: Toko dengan ID {$data['idStore']} tidak ditemukan.\n";
            }
        }
    }
}
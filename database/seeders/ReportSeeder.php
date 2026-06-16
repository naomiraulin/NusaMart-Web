<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Review;
use App\Models\Report;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil data dari JSON (Ini adalah contoh struktur, silakan diganti jika kamu punya JSON sendiri)
        $jsonString = '[
          {
            "idReport": "RPT-000001",
            "reporterId": "BYR-000001",
            "type": "product",
            "referenceId": "PRD-000001",
            "reason": "Produk ini sepertinya barang tiruan/palsu."
          },
          {
            "idReport": "RPT-000002",
            "reporterId": "BYR-000001",
            "type": "review",
            "referenceId": "REV-000001",
            "reason": "Ulasan mengandung kata-kata yang tidak pantas."
          },
          {
            "idReport": "RPT-000003",
            "reporterId": "BYR-000001",
            "type": "others",
            "referenceId": null,
            "reason": "Saya menemukan bug saat checkout pesanan."
          }
        ]';

        $reports = json_decode($jsonString, true);

        // 2. Alur validasi dan eksekusi
        foreach ($reports as $data) {
            // Pastikan user yang melaporkan benar-benar ada di database
            $reporterExists = User::where('idUser', $data['reporterId'])->exists();

            // Validasi referensi bersifat dinamis tergantung tipe laporannya
            $referenceValid = true; 
            
            if ($data['type'] === 'product' && $data['referenceId'] !== null) {
                $referenceValid = Product::where('idProduct', $data['referenceId'])->exists();
            } elseif ($data['type'] === 'review' && $data['referenceId'] !== null) {
                $referenceValid = Review::where('idReview', $data['referenceId'])->exists();
            }

            // Eksekusi jika pelapor dan referensinya valid
            if ($reporterExists && $referenceValid) {
                Report::create([
                    // Jika kamu menggunakan auto-generate ID di sistemmu, kolom ini bisa disesuaikan
                    'idReport'    => $data['idReport'], 
                    'reporterId'  => $data['reporterId'],
                    'type'        => $data['type'],
                    'referenceId' => $data['referenceId'],
                    'reason'      => $data['reason'],
                ]);
            } else {
                // Opsional: Logika jika data gagal dimasukkan karena referensi tidak ditemukan
                // echo "Gagal menambah Laporan: Reporter atau Referensi ({$data['type']}) tidak ditemukan.\n";
            }
        }
    }
}
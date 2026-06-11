<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\ReviewImage;
use Illuminate\Database\Seeder;

class ReviewImageSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil data dari JSON
        $jsonString = '[
          {
            "idRevImage": "RVI-000001",
            "idReview": "REV-000001",
            "urlImage": 0
          }
        ]';

        $reviewImages = json_decode($jsonString, true);

        // 2. Alur validasi dan eksekusi
        foreach ($reviewImages as $data) {
            // Cek apakah idReview benar-benar ada di tabel reviews
            $reviewExists = Review::where('idReview', $data['idReview'])->exists();

            // Jika ulasannya ditemukan, masukkan gambar ulasan ini ke database
            if ($reviewExists) {
                ReviewImage::create([
                    'idRevImage' => $data['idRevImage'],
                    'idReview'   => $data['idReview'],
                    
                    // Jika di database kolom urlImage bertipe String/Varchar, nilai 0 ini 
                    // akan otomatis diubah menjadi "0" oleh Laravel.
                    'urlImage'   => $data['urlImage'],
                ]);
            } else {
                // Opsional: Jika ingin melihat log saat relasi tidak ditemukan
                // echo "Gagal menambah gambar: Review dengan ID {$data['idReview']} tidak ditemukan.\n";
            }
        }
    }
}
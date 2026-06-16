<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil data dari JSON
        $jsonString = '[
        {
            "idReview": "REV-000001",
            "idOrderItem": "OIT-000001",
            "idUser": "BYR-000001",
            "rating": 5.0,
            "comment": "Kain batiknya sangat halus, motifnya presisi, dan warnanya persis seperti di foto. Pengiriman juga sangat cepat. Terima kasih!",
            "isHidden": false,
            "createAt": "2026-05-14T14:15:00"
          }
        ]';

        $reviews = json_decode($jsonString, true);

        // 2. Alur validasi dan eksekusi
        foreach ($reviews as $data) {
            // Cek apakah item pesanan (OrderItem) dan pembeli (User) benar-benar ada di database
            $orderItemExists = OrderItem::where('idOrderItem', $data['idOrderItem'])->exists();
            $userExists = User::where('idUser', $data['idUser'])->exists();

            // Jika relasi keduanya valid, masukkan ulasan ke database
            if ($orderItemExists && $userExists) {
                Review::create([
                    'idReview'    => $data['idReview'],
                    'idOrderItem' => $data['idOrderItem'],
                    'idUser'      => $data['idUser'],
                    
                    // Casting ke float untuk memastikan rating tersimpan sebagai desimal
                    'rating'      => (float) $data['rating'], 
                    
                    'comment'     => $data['comment'],
                    'isHidden'    => $data['isHidden'],
                    'createAt'    => $data['createAt'],
                ]);
            } else {
                // Opsional: Untuk mendeteksi jika data gagal dimasukkan karena relasi tidak lengkap
                // echo "Gagal menambah Review: OrderItem atau User tidak ditemukan.\n";
            }
        }
    }
}
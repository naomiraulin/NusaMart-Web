<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Order;
use App\Models\Notification;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil data dari JSON
        $jsonString = '[
          {
            "idNotif": "NTF-000001",
            "idUser": "BYR-000001",
            "title": "Selamat Datang di NusaMart!",
            "body": "Halo Trisa! Terima kasih sudah bergabung di NusaMart. Temukan berbagai produk UMKM lokal terbaik dari seluruh penjuru Nusantara di sini.",
            "type": "SISTEM",
            "isRead": true,
            "createAt": "2026-05-13T09:00:00",
            "referenceId": null,
            "referenceType": null
          },
          {
            "idNotif": "NTF-000002",
            "idUser": "BYR-000001",
            "title": "Promo Akhir Pekan Tiba",
            "body": "Nikmati gratis ongkir dan diskon hingga 50% untuk kategori Fashion Lokal. Berlaku hanya sampai akhir pekan ini, jangan sampai ketinggalan!",
            "type": "SISTEM",
            "isRead": false,
            "createAt": "2026-05-14T08:00:00",
            "referenceId": null,
            "referenceType": null
          },
          {
            "idNotif": "NTF-000003",
            "idUser": "BYR-000001",
            "title": "Pesananmu sedang diproses",
            "body": "Hore! Penjual sedang menyiapkan pesananmu dengan nomor pesanan ORD-000001. Silakan tunggu update resi pengiriman.",
            "type": "ORDER",
            "isRead": true,
            "createAt": "2026-05-14T10:15:00",
            "referenceId": "ORD-000001",
            "referenceType": "ORDER"
          },
          {
            "idNotif": "NTF-000004",
            "idUser": "BYR-000001",
            "title": "Pesananmu telah dikirim!",
            "body": "Paketmu untuk pesanan ORD-000001 telah diserahkan ke kurir JNE Reguler. Kamu bisa melacak status pengirimannya sekarang.",
            "type": "ORDER",
            "isRead": false,
            "createAt": "2026-05-14T15:05:00",
            "referenceId": "ORD-000001",
            "referenceType": "ORDER"
          },
          {
            "idNotif": "NTF-000005",
            "idUser": "BYR-000001",
            "title": "Pembayaran Berhasil Diverifikasi",
            "body": "Pembayaran kamu untuk pesanan ORD-000002 telah berhasil kami terima. Pesananmu akan segera diproses oleh penjual.",
            "type": "ORDER",
            "isRead": false,
            "createAt": "2026-05-14T11:05:00",
            "referenceId": "ORD-000002",
            "referenceType": "PAYMENT"
          }
        ]';

        $notifications = json_decode($jsonString, true);

        // 2. Alur validasi dan eksekusi
        foreach ($notifications as $data) {
            // Cek apakah user (penerima notifikasi) benar-benar ada di database
            $userExists = User::where('idUser', $data['idUser'])->exists();

            // Validasi tambahan: Jika notifikasi memiliki referensi pesanan, pastikan pesanan tersebut ada
            $referenceValid = true;
            if ($data['referenceId'] !== null && str_starts_with($data['referenceId'], 'ORD-')) {
                $referenceValid = Order::where('idOrder', $data['referenceId'])->exists();
            }

            // Jika user dan referensinya valid, masukkan ke database
            if ($userExists && $referenceValid) {
                Notification::create([
                    'idNotif'       => $data['idNotif'],
                    'idUser'        => $data['idUser'],
                    'title'         => $data['title'],
                    'body'          => $data['body'],
                    'type'          => $data['type'],
                    'isRead'        => $data['isRead'],
                    'referenceId'   => $data['referenceId'],
                    'referenceType' => $data['referenceType'],
                    
                    // Gunakan createAt dari JSON agar urutan waktunya rapi saat ditampilkan di aplikasi
                    'createAt'      => $data['createAt'], 
                ]);
            } else {
                // Opsional: Untuk mendeteksi jika data gagal dimasukkan
                // echo "Gagal menambah Notifikasi: User atau Referensi Order tidak ditemukan.\n";
            }
        }
    }
}
<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\BadgeVerification;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BadgeVerificationSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua toko dari database
        $stores = Store::all();
        $counter = 1;

        foreach ($stores as $store) {
            // Simulasi: Beri peluang 70% bahwa toko ini pernah mengajukan badge
            if (rand(1, 100) <= 70) {
                
                // Acak status pengajuannya agar data bervariasi
                $statusOptions = ['PENDING', 'APPROVED', 'REJECTED'];
                $status = $statusOptions[array_rand($statusOptions)];

                // Buat tanggal pengajuan (mundur beberapa hari ke belakang)
                $requestDate = Carbon::now()->subDays(rand(10, 30));
                
                // Jika statusnya bukan PENDING, berarti sudah direview beberapa hari setelah request
                $reviewDate = $status !== 'PENDING' ? (clone $requestDate)->addDays(rand(1, 3)) : null;
                
                // Jika APPROVED, masa berlakunya 1 tahun dari tanggal review
                $endDate = $status === 'APPROVED' ? (clone $reviewDate)->addYear() : null;

                // Tentukan catatan admin berdasarkan status
                $notes = match($status) {
                    'APPROVED' => 'Telah diverifikasi sebagai produk asli UMKM lokal.',
                    'REJECTED' => 'Dokumen izin usaha UMKM tidak lengkap atau tidak valid.',
                    'PENDING'  => 'Menunggu pengecekan dokumen oleh tim admin.',
                };

                BadgeVerification::create([
                    'idBadge'     => 'BDG-' . str_pad($counter++, 6, '0', STR_PAD_LEFT),
                    'idStore'     => $store->idStore,
                    'badgeType'   => 'LOCAL',
                    'requestDate' => $requestDate,
                    'reviewDate'  => $reviewDate,
                    'endDate'     => $endDate,
                    'status'      => $status,
                    'notes'       => $notes,
                ]);
            }
        }
    }
}
<?php

namespace Database\Factories;

use App\Models\BadgeVerification;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BadgeVerificationFactory extends Factory
{
    public function definition(): array
    {
        $status = fake()->randomElement(['PENDING', 'APPROVED', 'REJECTED', 'EXPIRED']);
        $requestDate = fake()->dateTimeBetween('-2 months', 'now');
        
        // Logika tanggal dan catatan berdasarkan status
        $reviewDate = ($status !== 'PENDING') ? Carbon::instance($requestDate)->addDays(rand(1, 3)) : null;
        $endDate = ($status === 'APPROVED') ? Carbon::instance($reviewDate)->addYear() : null;
        
        $notes = null;
        if ($status === 'REJECTED') {
            $notes = fake()->randomElement(['Dokumen tidak lengkap', 'Lokasi toko tidak valid']);
        } elseif ($status === 'APPROVED') {
            $notes = 'Verifikasi berhasil. Badge aktif selama 1 tahun.';
        }

        return [
            'idBadge' => Str::uuid()->toString(),
            // idStore diisi dari seeder
            'badgeType' => 'VERIFIED LOCAL',
            'requestDate' => $requestDate,
            'reviewDate' => $reviewDate,
            'endDate' => $endDate,
            'status' => $status,
            'notes' => $notes,
        ];
    }
}
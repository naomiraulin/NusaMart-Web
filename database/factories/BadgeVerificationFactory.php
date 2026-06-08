<?php

namespace Database\Factories;

use App\Models\BadgeVerification;
use App\Services\IdGeneratorService;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class BadgeVerificationFactory extends Factory
{
    public function definition(): array
    {
        $status = fake()->randomElement(['PENDING', 'APPROVED', 'REJECTED', 'EXPIRED']);
        $requestDate = fake()->dateTimeBetween('-2 months', 'now');

        $reviewDate = ($status !== 'PENDING') ? Carbon::instance($requestDate)->addDays(rand(1, 3)) : null;
        $endDate = ($status === 'APPROVED') ? Carbon::instance($reviewDate)->addYear() : null;

        $notes = null;
        if ($status === 'REJECTED') {
            $notes = fake()->randomElement(['Dokumen tidak lengkap', 'Lokasi toko tidak valid']);
        } elseif ($status === 'APPROVED') {
            $notes = 'Verifikasi berhasil. Badge aktif selama 1 tahun.';
        }

        $idGenerator = app(IdGeneratorService::class);

        return [
            'idBadge'      => $idGenerator->generate('BDG', BadgeVerification::class, 'idBadge'),
            'badgeType'    => 'VERIFIED LOCAL',
            'requestDate'  => $requestDate,
            'reviewDate'   => $reviewDate,
            'endDate'      => $endDate,
            'status'       => $status,
            'notes'        => $notes,
        ];
    }
}
<?php

namespace Database\Factories;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class NotificationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'idNotif' => Str::uuid()->toString(),
            // idUser akan disuntikkan via Seeder
            'title' => 'Pembaruan Sistem',
            'body' => 'Nikmati fitur terbaru dari aplikasi kami untuk pengalaman belanja yang lebih baik.',
            'type' => 'SISTEM',
            'isRead' => fake()->boolean(60),
            'referenceId' => null,
            'referenceType' => 'SYSTEM',
        ];
    }
}
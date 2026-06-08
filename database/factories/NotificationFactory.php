<?php

namespace Database\Factories;

use App\Models\Notification;
use App\Services\IdGeneratorService;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'idNotif'       => app(IdGeneratorService::class)->generate('NTF', Notification::class, 'idNotif'),
            'title'         => 'Pembaruan Sistem',
            'body'          => 'Nikmati fitur terbaru dari aplikasi kami untuk pengalaman belanja yang lebih baik.',
            'type'          => 'SISTEM',
            'isRead'        => fake()->boolean(60),
            'referenceId'   => null,
            'referenceType' => 'SYSTEM',
            'createAt'      => now(),
        ];
    }
}
<?php

namespace Database\Factories;

use App\Models\Report;
use App\Services\IdGeneratorService;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportFactory extends Factory
{
    public function definition(): array
    {
        $status = fake()->randomElement(['OPEN', 'REVIEWED', 'RESOLVED', 'DISMISSED']);

        return [
            'idReport'  => app(IdGeneratorService::class)->generate('RPT', Report::class, 'idReport'),
            'reason'    => fake()->sentence(),
            'status'    => $status,
            'adminNote' => in_array($status, ['RESOLVED', 'DISMISSED']) ? 'Telah ditindaklanjuti oleh Admin.' : null,
            'createAt'  => now(),
            'updateAt'  => now(),
        ];
    }
}
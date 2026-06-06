<?php

namespace Database\Factories;

use App\Models\Report;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ReportFactory extends Factory
{
    public function definition(): array
    {
        $status = fake()->randomElement(['OPEN', 'REVIEWED', 'RESOLVED', 'DISMISSED']);
        
        return [
            'idReport' => Str::uuid()->toString(),
            'reason' => fake()->sentence(), // Ubah bagian ini menjadi sentence()
            'status' => $status,
            'adminNote' => in_array($status, ['RESOLVED', 'DISMISSED']) ? 'Telah ditindaklanjuti oleh Admin.' : null,
        ];
    }
}
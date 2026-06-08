<?php

namespace Database\Factories;

use App\Models\Shipping;
use App\Services\IdGeneratorService;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class ShippingFactory extends Factory
{
    public function definition(): array
    {
        $status       = fake()->randomElement(['WAITING', 'PICKED_UP', 'IN_TRANSIT', 'DELIVERED', 'FAILED']);
        $shippingDate = ($status !== 'WAITING') ? fake()->dateTimeBetween('-2 weeks', 'now') : null;
        $deliveredDate = ($status === 'DELIVERED') ? Carbon::instance($shippingDate)->addDays(rand(1, 4)) : null;
        $resi         = ($status !== 'WAITING') ? strtoupper(fake()->bothify('AWB#########ID')) : null;

        return [
            'idShipping'    => app(IdGeneratorService::class)->generate('SHP', Shipping::class, 'idShipping'),
            'resi'          => $resi,
            'shippingPrice' => fake()->randomFloat(2, 10000, 50000),
            'shippingStatus' => $status,
            'shippingDate'  => $shippingDate,
            'deliveredDate' => $deliveredDate,
        ];
    }
}
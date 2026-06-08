<?php

namespace Database\Factories;

use App\Models\ShippingTracking;
use App\Services\IdGeneratorService;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShippingTrackingFactory extends Factory
{
    public function definition(): array
    {
        $locations = [
            'Fasilitas Logistik Jakarta',
            'Gudang Transit Bandung',
            'Kantor Cabang Surabaya',
            'Drop Point Yogyakarta',
            'Hub Pengiriman Semarang',
        ];
        $descriptions = [
            'Paket telah diberangkatkan dari fasilitas logistik asal',
            'Paket sedang dalam perjalanan menuju kota tujuan',
            'Paket telah tiba di gudang transit',
            'Paket sedang dibawa kurir menuju alamat penerima',
            'Pesanan telah diserahkan kepada pihak ekspedisi',
        ];

        return [
            'idTracking'     => app(IdGeneratorService::class)->generate('TRK', ShippingTracking::class, 'idTracking'),
            'packetLocation' => fake()->randomElement($locations),
            'description'    => fake()->randomElement($descriptions),
            'updateAt'       => now(),
        ];
    }
}
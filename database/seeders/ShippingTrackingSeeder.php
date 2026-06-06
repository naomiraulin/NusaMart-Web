<?php

namespace Database\Seeders;

use App\Models\Shipping;
use App\Models\ShippingTracking;
use Illuminate\Database\Seeder;

class ShippingTrackingSeeder extends Seeder
{
    public function run(): void
    {
        // Hanya ambil pengiriman yang resinya sudah keluar (sudah diproses/dikirim)
        $shippings = Shipping::whereNotNull('resi')->get();

        if ($shippings->count() > 0) {
            foreach ($shippings as $shipping) {
                // Buat 2 sampai 4 riwayat pelacakan per pengiriman
                ShippingTracking::factory(rand(2, 4))->create([
                    'idShipping' => $shipping->idShipping,
                ]);

                // Jika statusnya DELIVERED, tambahkan satu riwayat penutup yang logis
                if ($shipping->shippingStatus === 'DELIVERED') {
                    ShippingTracking::factory()->create([
                        'idShipping' => $shipping->idShipping,
                        'packetLocation' => 'Alamat Tujuan',
                        'description' => 'Paket telah diterima dengan baik oleh pembeli.',
                    ]);
                }
            }
        }
    }
}
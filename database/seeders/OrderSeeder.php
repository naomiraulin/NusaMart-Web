<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Store;
use App\Models\UserAddress;
use App\Models\Order;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $buyers = User::where('role', 'BUYER')->get();
        $stores = Store::all();

        // Pastikan ada toko dan pembeli sebelum membuat order
        if ($buyers->count() > 0 && $stores->count() > 0) {
            foreach ($buyers as $buyer) {
                // Ambil alamat utama user ini
                $address = UserAddress::where('idUser', $buyer->idUser)->where('isDefault', true)->first();
                
                if ($address) {
                    // Buat 1 hingga 3 pesanan secara acak untuk pembeli ini
                    $randomStores = $stores->random(rand(1, 3));
                    
                    foreach ($randomStores as $store) {
                        Order::factory()->create([
                            'idUser' => $buyer->idUser,
                            'idStore' => $store->idStore,
                            'idAddress' => $address->idAddress,
                        ]);
                    }
                }
            }
        }
    }
}
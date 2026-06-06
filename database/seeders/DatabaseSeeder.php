<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Pintu utama akan memanggil UserSeeder
        $this->call([
            UserSeeder::class,
            SellerSeeder::class,
            AdminSeeder::class,
            UserAddressSeeder::class,
            StoreSeeder::class,
            BadgeVerificationSeeder::class,
            CategorySeeder::class,
            SubCategorySeeder::class,
            ProductSeeder::class,
            ProductSubCategorySeeder::class,
            ProductItemSeeder::class,
            ProductVariationSeeder::class,
            ProductImageSeeder::class,
            CartSeeder::class,
            CartItemSeeder::class,
            PaymentMethodSeeder::class,
            PaymentMethodSeeder::class,
            StoreWalletSeeder::class,
            WithdrawalSeeder::class,
        ]);
    }
}
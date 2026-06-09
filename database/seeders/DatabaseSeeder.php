<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            SellerSeeder::class,
            AdminSeeder::class,
            UserAddressSeeder::class,
            CategorySeeder::class,
            SubCategorySeeder::class,
            CourierOptionSeeder::class,
            PaymentMethodSeeder::class,
            StoreSeeder::class,
            BadgeVerificationSeeder::class,
            ProductSeeder::class,
            ProductSubCategorySeeder::class,
            ProductItemSeeder::class,
            ProductVariationSeeder::class,
            ProductImageSeeder::class,
            CartSeeder::class,
            CartItemSeeder::class,
            OrderSeeder::class,
            OrderItemSeeder::class,
            PaymentSeeder::class,
            ShippingSeeder::class,
            ShippingTrackingSeeder::class,
            StoreWalletSeeder::class,
            WithdrawalSeeder::class,
            WalletTransactionSeeder::class,
            ReviewSeeder::class,
            ReviewImageSeeder::class,
            RoomChatSeeder::class,
            ChatSeeder::class,
            NotificationSeeder::class,
            ReportSeeder::class,
        ]);
    }
}
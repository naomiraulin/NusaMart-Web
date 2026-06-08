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
        $this->call([
            UserSeeder::class,           // 1. User dulu
            AdminSeeder::class,          // 2. Admin (butuh User)
            UserAddressSeeder::class,    // 3. Alamat (butuh User)
            CategorySeeder::class,       // 4. Kategori
            SubCategorySeeder::class,    // 5. Sub kategori (butuh Category)
            CourierOptionSeeder::class,  // 6. Kurir
            PaymentMethodSeeder::class,  // 7. Metode pembayaran
            StoreSeeder::class,          // 8. Toko (butuh Seller dari User)
            BadgeVerificationSeeder::class, // 9. Badge (butuh Store)
            ProductSeeder::class,        // 10. Produk (butuh Store)
            ProductSubCategorySeeder::class, // 11. (butuh Product + SubCategory)
            ProductItemSeeder::class,    // 12. Item produk (butuh Product)
            ProductVariationSeeder::class,   // 13. Variasi (butuh ProductItem)
            ProductImageSeeder::class,   // 14. Foto produk (butuh Product)
            CartSeeder::class,           // 15. Keranjang (butuh User BUYER)
            CartItemSeeder::class,       // 16. Isi keranjang (butuh Cart + ProductItem)
            OrderSeeder::class,          // 17. Order (butuh User + Store + Address)
            OrderItemSeeder::class,      // 18. Item order (butuh Order + ProductItem)
            PaymentSeeder::class,        // 19. Pembayaran (butuh Order)
            ShippingSeeder::class,       // 20. Pengiriman (butuh Order + Courier)
            ShippingTrackingSeeder::class,   // 21. Tracking (butuh Shipping)
            StoreWalletSeeder::class,    // 22. Wallet (butuh Store)
            WithdrawalSeeder::class,     // 23. Penarikan (butuh StoreWallet)
            WalletTransactionSeeder::class,  // 24. Transaksi wallet (butuh Order + Withdrawal)
            ReviewSeeder::class,         // 25. Ulasan (butuh OrderItem DELIVERED)
            ReviewImageSeeder::class,    // 26. Foto ulasan (butuh Review)
            RoomChatSeeder::class,       // 27. Room chat (butuh User)
            ChatSeeder::class,           // 28. Chat (butuh RoomChat)
            NotificationSeeder::class,   // 29. Notifikasi (butuh User + Order)
            ReportSeeder::class,         // 30. Laporan (butuh User + Product + Review)
        ]);
    }
}
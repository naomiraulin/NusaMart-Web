<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            // Menggunakan idProduct sebagai Primary Key pengganti id() bawaan
            $table->id('idProduct'); 
            
            // Foreign Key untuk relasi ke toko/store
            $table->foreignId('idStore')->constrained('stores')->onDelete('cascade'); 
            
            $table->string('productName');
            $table->text('description')->nullable(); // nullable jika deskripsi boleh kosong
            $table->integer('weightGram');
            
            // Kolom status, misalnya: 'aktif', 'nonaktif', atau 'habis'
            $table->string('productStatus')->default('active'); 
            
            // Nilai rating rata-rata (desimal), default 0.00
            $table->decimal('avgRating', 3, 2)->default(0.00); 
            
            // Jumlah produk terjual
            $table->integer('sold')->default(0);
            
            // createAt & updateAt menggunakan default timestamp bawaan Laravel (custom column name)
            $table->timestamp('createAt')->useCurrent();
            $table->timestamp('updateAt')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
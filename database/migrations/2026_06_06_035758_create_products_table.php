<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->string('idProduct')->primary();
            
            // Foreign Key ke tabel stores
            $table->string('idStore');
            
            $table->string('productName');
            $table->text('description')->nullable();
            $table->integer('weightGram');
            
            // Menggunakan string untuk status, misal: 'ACTIVE', 'DRAFT', 'OUT_OF_STOCK'
            $table->string('productStatus')->default('ACTIVE');
            
            // avgRating Double (menggunakan decimal untuk rating seperti 4.5)
            $table->decimal('avgRating', 3, 1)->default(0.0);
            
            // Sesuai catatanmu: default = 0
            $table->integer('sold')->default(0);

            // Mendefinisikan relasi ke tabel stores (cascade agar jika toko tutup/dihapus, produknya ikut terhapus)
            $table->foreign('idStore')->references('idStore')->on('stores')->onDelete('cascade');

            // Custom Timestamps
            $table->dateTime('createAt')->nullable();
            $table->dateTime('updateAt')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
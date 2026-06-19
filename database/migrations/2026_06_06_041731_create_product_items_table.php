<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_items', function (Blueprint $table) {
            $table->string('idItem')->primary();
            
            // Foreign Key
            $table->string('idProduct');
            
            // SKU (Stock Keeping Unit) biasanya bersifat unik untuk setiap variasi
            $table->string('sku')->nullable()->unique();
            $table->integer('stock')->default(0);
            $table->double('price');
            $table->boolean('isActive')->default(true);

            // Mendefinisikan relasi ke tabel products (cascade agar jika produk dihapus, variasinya ikut terhapus)
            $table->foreign('idProduct')->references('idProduct')->on('products')->onDelete('cascade');

            // Custom Timestamps
            $table->dateTime('createAt')->nullable();
            $table->dateTime('updateAt')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_items');
    }
};
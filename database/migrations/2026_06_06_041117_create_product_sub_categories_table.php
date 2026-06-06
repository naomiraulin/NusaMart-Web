<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_sub_categories', function (Blueprint $table) {
            $table->string('idProductSubCat')->primary();
            
            // Foreign Keys
            $table->string('idProduct');
            $table->string('idSubCategory');

            // Mendefinisikan relasi
            $table->foreign('idProduct')->references('idProduct')->on('products')->onDelete('cascade');
            $table->foreign('idSubCategory')->references('idSubCategory')->on('sub_categories')->onDelete('cascade');

            // Custom Timestamps (opsional untuk tabel pivot, tapi kita buat konsisten)
            $table->dateTime('createAt')->nullable();
            $table->dateTime('updateAt')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_sub_categories');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->string('idImage')->primary();
            
            // Foreign Key ke tabel products
            $table->string('idProduct');
            
            $table->string('imageURL');
            $table->boolean('isPrimary')->default(false);

            // Relasi dengan cascade delete
            $table->foreign('idProduct')->references('idProduct')->on('products')->onDelete('cascade');

            // Custom Timestamps
            $table->dateTime('createAt')->nullable();
            $table->dateTime('updateAt')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variations', function (Blueprint $table) {
            $table->string('idVariation')->primary();
            
            // Foreign Key ke tabel product_items
            $table->string('idItem');
            
            // Contoh typeVariation: 'Warna', 'Ukuran', 'Bahan'
            $table->string('typeVariation');
            
            // Contoh value: 'Merah', 'XL', 'Katun'
            $table->string('value');

            // Definisi relasi dengan cascade delete
            $table->foreign('idItem')->references('idItem')->on('product_items')->onDelete('cascade');

            // Custom Timestamps
            $table->dateTime('createAt')->nullable();
            $table->dateTime('updateAt')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variations');
    }
};
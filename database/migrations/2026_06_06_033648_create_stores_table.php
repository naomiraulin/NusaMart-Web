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
        Schema::create('stores', function (Blueprint $table) {
            $table->string('idStore')->primary();
            
            // Foreign Key ke tabel sellers
            $table->string('idSeller');
            
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('logoURL')->nullable();
            $table->string('location');
            $table->string('urlLocation')->nullable(); // Biasanya link Google Maps
            
            // storeRating Double (menggunakan decimal untuk rating misal 4.5)
            $table->decimal('storeRating', 3, 1)->default(0.0);
            
            $table->boolean('isActive')->default(true);

            // Mendefinisikan relasi ke tabel sellers
            // Kita pakai cascade agar jika seller dihapus, tokonya ikut terhapus
            $table->foreign('idSeller')->references('idSeller')->on('sellers')->onDelete('cascade');

            $table->dateTime('createAt')->nullable();
            $table->dateTime('updateAt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
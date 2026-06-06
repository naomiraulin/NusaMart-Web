<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_trackings', function (Blueprint $table) {
            $table->string('idTracking')->primary();
            
            // Foreign Key ke tabel shippings
            $table->string('idShipping');
            
            $table->string('packetLocation');
            $table->string('description'); // Contoh: "Paket telah diserahkan ke kurir"

            // Relasi dengan cascade delete
            $table->foreign('idShipping')->references('idShipping')->on('shippings')->onDelete('cascade');

            // Custom Timestamps
            $table->dateTime('createAt')->nullable();
            $table->dateTime('updateAt')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_trackings');
    }
};
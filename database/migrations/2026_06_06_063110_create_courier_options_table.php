<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courier_options', function (Blueprint $table) {
            $table->string('idCourier')->primary();
            
            $table->string('courierName');
            
            // Sesuai catatanmu: misal REGULER / KARGO. Kita bisa pakai string agar lebih fleksibel 
            // jika nanti ada tambahan tipe seperti INSTANT atau NEXT DAY
            $table->string('serviceType');
            
            // Estimasi waktu dalam hitungan hari (bisa null)
            $table->integer('timeEstimation')->nullable();
            
            $table->boolean('isActive')->default(true);

            // Custom Timestamps
            $table->dateTime('createAt')->nullable();
            $table->dateTime('updateAt')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courier_options');
    }
};
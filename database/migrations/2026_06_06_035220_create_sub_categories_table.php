<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sub_categories', function (Blueprint $table) {
            $table->string('idSubCategory')->primary();
            
            // Foreign Key ke tabel categories
            $table->string('idCategory');
            
            $table->string('subCategoryName');
            $table->text('description')->nullable();

            // Mendefinisikan relasi ke tabel categories (cascade agar jika kategori dihapus, sub-nya ikut terhapus)
            $table->foreign('idCategory')->references('idCategory')->on('categories')->onDelete('cascade');

            // Custom Timestamps
            $table->dateTime('createAt')->nullable();
            $table->dateTime('updateAt')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_categories');
    }
};
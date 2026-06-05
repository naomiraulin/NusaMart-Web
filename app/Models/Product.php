<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // 1. Beritahu Laravel bahwa Primary Key tabel ini adalah idProduct, bukan 'id'
    protected $primaryKey = 'idProduct';

    // 2. Beritahu Laravel nama kolom timestamp kustommu
    const CREATED_AT = 'createAt';
    const UPDATED_AT = 'updateAt';

    // 3. Daftarkan kolom yang diizinkan untuk diisi massal (Mass Assignment)
    protected $fillable = [
        'idStore',
        'productName',
        'description',
        'weightGram',
        'productStatus',
        'avgRating',
        'sold'
    ];

    // 4. Opsional: Jika kamu ingin otomatis mengubah tipe data saat diakses di Kotlin nanti
    protected $casts = [
        'avgRating' => 'float',
        'weightGram' => 'integer',
        'sold' => 'integer',
    ];
}
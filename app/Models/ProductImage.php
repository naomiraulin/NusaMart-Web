<?php

namespace App\Models;

use Database\Factories\ProductImageFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['idImage', 'idProduct', 'imageURL', 'isPrimary'])]
class ProductImage extends Model
{
    /** @use HasFactory<ProductImageFactory> */
    use HasFactory;

    // --- Konfigurasi Custom Tabel Product Images ---
    protected $primaryKey = 'idImage';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'createAt';
    const UPDATED_AT = 'updateAt';
    // -----------------------------------------------

    /**
     * Relasi Balik ke Model Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'idProduct', 'idProduct');
    }
}
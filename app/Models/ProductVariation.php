<?php

namespace App\Models;

use Database\Factories\ProductVariationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['idVariation', 'idItem', 'typeVariation', 'value'])]
class ProductVariation extends Model
{
    /** @use HasFactory<ProductVariationFactory> */
    use HasFactory;

    // --- Konfigurasi Custom Tabel Product Variations ---
    protected $primaryKey = 'idVariation';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'createAt';
    const UPDATED_AT = 'updateAt';
    // ---------------------------------------------------

    /**
     * Relasi Balik ke Model ProductItem
     */
    public function productItem()
    {
        return $this->belongsTo(ProductItem::class, 'idItem', 'idItem');
    }
}
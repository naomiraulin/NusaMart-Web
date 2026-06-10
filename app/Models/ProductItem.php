<?php

namespace App\Models;

use Database\Factories\ProductItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['idItem', 'idProduct', 'sku', 'stock', 'price', 'isActive'])]
class ProductItem extends Model
{
    /** @use HasFactory<ProductItemFactory> */
    use HasFactory;

    protected $primaryKey = 'idItem';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'createAt';
    const UPDATED_AT = 'updateAt';

    // Relasi ke Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'idProduct', 'idProduct');
    }

    public function productVariations()
    {
        return $this->hasMany(ProductVariation::class, 'idItem', 'idItem');
    }
}
<?php

namespace App\Models;

use Database\Factories\CartItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['idCartItem', 'idCart', 'idItem', 'quantity'])]
class CartItem extends Model
{
    /** @use HasFactory<CartItemFactory> */
    use HasFactory;

    // --- Konfigurasi Custom Tabel Cart Items ---
    protected $primaryKey = 'idCartItem';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'createAt';
    const UPDATED_AT = 'updateAt';
    // ------------------------------------------

    // Relasi ke Cart
    public function cart()
    {
        return $this->belongsTo(Cart::class, 'idCart', 'idCart');
    }

    // Relasi ke ProductItem (SKU)
    public function productItem()
    {
        return $this->belongsTo(ProductItem::class, 'idItem', 'idItem');
    }
}
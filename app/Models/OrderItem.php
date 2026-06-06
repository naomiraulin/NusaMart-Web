<?php

namespace App\Models;

use Database\Factories\OrderItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['idOrderItem', 'idOrder', 'idItem', 'nameSnapshot', 'priceSnapshot', 'quantity'])]
class OrderItem extends Model
{
    /** @use HasFactory<OrderItemFactory> */
    use HasFactory;

    // --- Konfigurasi Custom Tabel Order Items ---
    protected $primaryKey = 'idOrderItem';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'createAt';
    const UPDATED_AT = 'updateAt';
    // ---------------------------------------------

    /**
     * Relasi Balik ke Model Order
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'idOrder', 'idOrder');
    }

    /**
     * Relasi Balik ke Model ProductItem (SKU)
     */
    public function productItem()
    {
        return $this->belongsTo(ProductItem::class, 'idItem', 'idItem');
    }
}
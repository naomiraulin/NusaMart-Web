<?php

namespace App\Models;

use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['idOrder', 'idUser', 'idStore', 'productTotalPrice', 'shippingCost', 'servicePrice', 'grandTotal', 'orderStatus', 'invoiceNumber', 'idAddress', 'orderDate', 'arrivedDate', 'buyerNote', 'idPayment'])]
class Order extends Model
{
    /** @use HasFactory<OrderFactory> */
    use HasFactory;

    protected $primaryKey = 'idOrder';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'createAt';
    const UPDATED_AT = 'updateAt';

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'idUser', 'idUser');
    }

    // Relasi ke Store
    public function store()
    {
        return $this->belongsTo(Store::class, 'idStore', 'idStore');
    }

    // Relasi ke UserAddress
    public function address()
    {
        return $this->belongsTo(UserAddress::class, 'idAddress', 'idAddress');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'idOrder', 'idOrder');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'idPayment', 'idPayment');
    }

    public function shipping()
    {
        return $this->hasOne(Shipping::class, 'idOrder', 'idOrder');
    }
}
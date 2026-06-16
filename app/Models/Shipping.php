<?php

namespace App\Models;

use Database\Factories\ShippingFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['idShipping', 'idOrder', 'idCourier', 'resi', 'shippingPrice', 'shippingStatus', 'shippingDate', 'deliveredDate'])]
class Shipping extends Model
{
    /** @use HasFactory<ShippingFactory> */
    use HasFactory;

    protected $primaryKey = 'idShipping';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'createAt';
    const UPDATED_AT = 'updateAt';

    public function order()
    {
        return $this->belongsTo(Order::class, 'idOrder', 'idOrder');
    }

    public function courier()
    {
        return $this->belongsTo(CourierOption::class, 'idCourier', 'idCourier');
    }

    public function shippingTrackings()
    {
        return $this->hasMany(ShippingTracking::class, 'idShipping', 'idShipping');
    }
}
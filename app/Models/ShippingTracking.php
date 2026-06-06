<?php

namespace App\Models;

use Database\Factories\ShippingTrackingFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['idTracking', 'idShipping', 'packetLocation', 'description'])]
class ShippingTracking extends Model
{
    /** @use HasFactory<ShippingTrackingFactory> */
    use HasFactory;

    // --- Konfigurasi Custom Tabel Shipping Tracking ---
    protected $primaryKey = 'idTracking';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'createAt';
    const UPDATED_AT = 'updateAt';
    // -------------------------------------------------

    /**
     * Relasi Balik ke Model Shipping
     */
    public function shipping()
    {
        return $this->belongsTo(Shipping::class, 'idShipping', 'idShipping');
    }
}
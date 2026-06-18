<?php

namespace App\Models;

use Database\Factories\StoreFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BadgeVerification;

#[Fillable(['idStore', 'idSeller', 'name', 'description', 'logoURL', 'location', 'urlLocation', 'storeRating', 'isActive'])]
class Store extends Model
{
    /** @use HasFactory<StoreFactory> */
    use HasFactory;

    // --- Konfigurasi Custom Tabel Stores ---
    protected $primaryKey = 'idStore';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'createAt';
    const UPDATED_AT = 'updateAt';
    // ---------------------------------------

    /**
     * Relasi Balik ke Model Seller
     */
    public function seller()
    {
        return $this->belongsTo(Seller::class, 'idSeller', 'idSeller');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'idStore', 'idStore');
    }

    public function badgeVerifications()
    {
        return $this->hasMany(BadgeVerification::class, 'idStore', 'idStore');
    }
}
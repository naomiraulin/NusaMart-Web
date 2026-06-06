<?php

namespace App\Models;

use Database\Factories\StoreFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
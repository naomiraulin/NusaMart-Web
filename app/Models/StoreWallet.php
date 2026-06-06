<?php

namespace App\Models;

use Database\Factories\StoreWalletFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['idWallet', 'idStore', 'activeBalance', 'outstandingBalance'])]
class StoreWallet extends Model
{
    /** @use HasFactory<StoreWalletFactory> */
    use HasFactory;

    // --- Konfigurasi Custom Tabel Store Wallets ---
    protected $primaryKey = 'idWallet';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'createAt';
    const UPDATED_AT = 'updateAt';
    // ---------------------------------------------

    /**
     * Relasi Balik ke Model Store
     */
    public function store()
    {
        return $this->belongsTo(Store::class, 'idStore', 'idStore');
    }
}
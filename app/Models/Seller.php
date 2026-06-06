<?php

namespace App\Models;

use Database\Factories\SellerFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['idSeller', 'nik', 'bankName', 'accountNumber'])]
class Seller extends Model
{
    /** @use HasFactory<SellerFactory> */
    use HasFactory;

    // --- Konfigurasi Custom Tabel Sellers ---
    protected $primaryKey = 'idSeller';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'createAt';
    const UPDATED_AT = 'updateAt';
    // ----------------------------------------

    /**
     * Relasi Balik ke Model User (Optional tapi sangat berguna)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'idSeller', 'idUser');
    }
}
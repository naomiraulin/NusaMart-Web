<?php

namespace App\Models;

use Database\Factories\CartFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['idCart', 'idUser'])]
class Cart extends Model
{
    /** @use HasFactory<CartFactory> */
    use HasFactory;

    // --- Konfigurasi Custom Tabel Carts ---
    protected $primaryKey = 'idCart';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'createAt';
    const UPDATED_AT = 'updateAt';
    // -------------------------------------

    /**
     * Relasi Balik ke Model User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'idUser', 'idUser');
    }
}
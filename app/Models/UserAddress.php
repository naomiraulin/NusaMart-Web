<?php

namespace App\Models;

use Database\Factories\UserAddressFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['idAddress', 'idUser', 'label', 'receiver', 'phone', 'completeAddress', 'city', 'province', 'postalCode', 'isDefault'])]
class UserAddress extends Model
{
    /** @use HasFactory<UserAddressFactory> */
    use HasFactory;

    // --- Konfigurasi Custom Tabel User Addresses ---
    protected $primaryKey = 'idAddress';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'createAt';
    const UPDATED_AT = 'updateAt';
    // -----------------------------------------------

    /**
     * Relasi Balik ke Model User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'idUser', 'idUser');
    }
}
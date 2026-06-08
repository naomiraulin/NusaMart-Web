<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    use HasFactory;

    // --- Konfigurasi Primary Key ---
    protected $primaryKey = 'idSeller';
    public $incrementing = false;
    protected $keyType = 'string';

    // --- Custom Timestamp Column Names ---
    const CREATED_AT = 'createAt';
    const UPDATED_AT = 'updateAt';

    // --- Mass Assignment ---
    protected $fillable = [
        'idSeller',
        'nik',
        'bankName',
        'accountNumber',
        'createAt',
        'updateAt',
    ];

    // --- Relasi ke User ---
    public function user()
    {
        return $this->belongsTo(User::class, 'idSeller', 'idUser');
    }
}
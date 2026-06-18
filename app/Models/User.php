<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // ← tambah import ini

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // --- Konfigurasi Primary Key ---
    protected $primaryKey = 'idUser';
    public $incrementing = false;
    protected $keyType = 'string';

    // --- Custom Timestamp Column Names ---
    const CREATED_AT = 'createAt';
    const UPDATED_AT = 'updateAt';

    // --- Mass Assignment ---
    protected $fillable = [
        'idUser',
        'username',
        'email',
        'password',
        'phone',
        'role',
        'imageURL',
        'createAt',
        'updateAt',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // --- Relasi ke Seller ---
    public function seller()
    {
        return $this->hasOne(Seller::class, 'idSeller', 'idUser');
    }

    public function getAuthIdentifierName(): string
    {
        return 'idUser';
    }

    public function getAuthIdentifier(): string
    {
        return $this->idUser;
    }
}
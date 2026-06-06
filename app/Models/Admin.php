<?php

namespace App\Models;

use Database\Factories\AdminFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['idAdmin', 'division', 'accessLevel'])]
class Admin extends Model
{
    /** @use HasFactory<AdminFactory> */
    use HasFactory;

    // --- Konfigurasi Custom Tabel Admins ---
    protected $primaryKey = 'idAdmin';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'createAt';
    const UPDATED_AT = 'updateAt';
    // ----------------------------------------

    /**
     * Relasi Balik ke Model User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'idAdmin', 'idUser');
    }
}
<?php

namespace App\Models;

use Database\Factories\RoomChatFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['idRoom', 'idUser1', 'idUser2', 'lastMessage'])]
class RoomChat extends Model
{
    /** @use HasFactory<RoomChatFactory> */
    use HasFactory;

    // --- Konfigurasi Custom Tabel Room Chat ---
    protected $primaryKey = 'idRoom';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'createAt';
    const UPDATED_AT = 'updateAt';
    // -----------------------------------------

    /**
     * Relasi ke User sebagai Pembeli (User 1)
     */
    public function buyer()
    {
        return $this->belongsTo(User::class, 'idUser1', 'idUser');
    }

    /**
     * Relasi ke User sebagai Penjual (User 2)
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'idUser2', 'idUser');
    }
}
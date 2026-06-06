<?php

namespace App\Models;

use Database\Factories\ChatFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['idChat', 'idRoom', 'senderId', 'messageText', 'isRead'])]
class Chat extends Model
{
    /** @use HasFactory<ChatFactory> */
    use HasFactory;

    // --- Konfigurasi Custom Tabel Chats ---
    protected $primaryKey = 'idChat';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'createAt';
    const UPDATED_AT = 'updateAt';
    // --------------------------------------

    /**
     * Relasi ke Model RoomChat
     */
    public function room()
    {
        return $this->belongsTo(RoomChat::class, 'idRoom', 'idRoom');
    }

    /**
     * Relasi ke Model User (Pengirim Pesan)
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'senderId', 'idUser');
    }
}
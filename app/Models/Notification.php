<?php

namespace App\Models;

use Database\Factories\NotificationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['idNotif', 'idUser', 'title', 'body', 'type', 'isRead', 'referenceId', 'referenceType'])]
class Notification extends Model
{
    /** @use HasFactory<NotificationFactory> */
    use HasFactory;

    // --- Konfigurasi Custom Tabel Notifications ---
    protected $primaryKey = 'idNotif';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'createAt';
    const UPDATED_AT = 'updateAt';
    // ---------------------------------------------

    /**
     * Relasi ke Model User (Penerima Notifikasi)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'idUser', 'idUser');
    }
}
<?php

namespace App\Models;

use Database\Factories\ReviewFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['idReview', 'idOrderItem', 'idUser', 'rating', 'comment', 'isHidden'])]
class Review extends Model
{
    /** @use HasFactory<ReviewFactory> */
    use HasFactory;

    // --- Konfigurasi Custom Tabel Reviews ---
    protected $primaryKey = 'idReview';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'createAt';
    const UPDATED_AT = 'updateAt';
    // ---------------------------------------

    /**
     * Relasi ke Model OrderItem
     */
    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class, 'idOrderItem', 'idOrderItem');
    }

    /**
     * Relasi ke Model User (Pembeli)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'idUser', 'idUser');
    }

    public function reviewImages()
    {
        return $this->hasMany(ReviewImage::class, 'idReview', 'idReview');
    }
}
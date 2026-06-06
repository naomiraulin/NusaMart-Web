<?php

namespace App\Models;

use Database\Factories\ReviewImageFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['idRevImage', 'idReview', 'urlImage'])]
class ReviewImage extends Model
{
    /** @use HasFactory<ReviewImageFactory> */
    use HasFactory;

    // --- Konfigurasi Custom Tabel Review Images ---
    protected $primaryKey = 'idRevImage';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'createAt';
    const UPDATED_AT = 'updateAt';
    // ---------------------------------------------

    /**
     * Relasi Balik ke Model Review
     */
    public function review()
    {
        return $this->belongsTo(Review::class, 'idReview', 'idReview');
    }
}
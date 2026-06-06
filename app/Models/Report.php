<?php

namespace App\Models;

use Database\Factories\ReportFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['idReport', 'reporterId', 'type', 'referenceId', 'reason', 'status', 'adminNote'])]
class Report extends Model
{
    /** @use HasFactory<ReportFactory> */
    use HasFactory;

    // --- Konfigurasi Custom Tabel Reports ---
    protected $primaryKey = 'idReport';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'createAt';
    const UPDATED_AT = 'updateAt';
    // ----------------------------------------

    /**
     * Relasi ke User (Siapa yang membuat laporan)
     */
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporterId', 'idUser');
    }

    /**
     * Relasi ke Target (User) jika type == 'user'
     */
    public function reportedUser()
    {
        return $this->belongsTo(User::class, 'referenceId', 'idUser');
    }

    /**
     * Relasi ke Target (Product) jika type == 'product'
     */
    public function reportedProduct()
    {
        return $this->belongsTo(Product::class, 'referenceId', 'idProduct');
    }

    /**
     * Relasi ke Target (Review) jika type == 'review'
     */
    public function reportedReview()
    {
        return $this->belongsTo(Review::class, 'referenceId', 'idReview');
    }

    /**
     * MAGIC FUNCTION: Mengambil data target secara otomatis berdasarkan 'type'
     */
    public function getTargetAttribute()
    {
        if ($this->type === 'user') return $this->reportedUser;
        if ($this->type === 'product') return $this->reportedProduct;
        if ($this->type === 'review') return $this->reportedReview;
        
        return null; // Untuk 'others'
    }
}
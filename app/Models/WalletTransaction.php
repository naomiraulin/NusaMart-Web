<?php

namespace App\Models;

use Database\Factories\WalletTransactionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['idTransaction', 'idWallet', 'mutationType', 'nominal', 'description', 'referenceId'])]
class WalletTransaction extends Model
{
    /** @use HasFactory<WalletTransactionFactory> */
    use HasFactory;

    // --- Konfigurasi Custom Tabel Wallet Transaction ---
    protected $primaryKey = 'idTransaction';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'createAt';
    const UPDATED_AT = 'updateAt';
    // ---------------------------------------------------

    /**
     * Relasi standar ke StoreWallet
     */
    public function wallet()
    {
        return $this->belongsTo(StoreWallet::class, 'idWallet', 'idWallet');
    }

    /**
     * Relasi ke Order (Hanya valid jika mutationType == 'IN')
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'referenceId', 'idOrder');
    }

    /**
     * Relasi ke Withdrawal (Hanya valid jika mutationType == 'OUT')
     */
    public function withdrawal()
    {
        return $this->belongsTo(Withdrawal::class, 'referenceId', 'idWithdrawal');
    }

    /**
     * MAGIC FUNCTION: Mengambil data referensi (Order/Withdrawal) secara otomatis
     */
    public function getSourceAttribute()
    {
        if ($this->mutationType === 'IN') {
            return $this->order; 
        } 
        
        if ($this->mutationType === 'OUT') {
            return $this->withdrawal; 
        }

        return null;
    }
}
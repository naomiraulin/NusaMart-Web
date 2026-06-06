<?php

namespace App\Models;

use Database\Factories\WithdrawalFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['idWithdrawal', 'idWallet', 'nominal', 'serviceCost', 'status', 'transferPic'])]
class Withdrawal extends Model
{
    /** @use HasFactory<WithdrawalFactory> */
    use HasFactory;

    // --- Konfigurasi Custom Tabel Withdrawals ---
    protected $primaryKey = 'idWithdrawal';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'createAt';
    const UPDATED_AT = 'updateAt';
    // -------------------------------------------

    /**
     * Relasi Balik ke Model StoreWallet
     */
    public function wallet()
    {
        return $this->belongsTo(StoreWallet::class, 'idWallet', 'idWallet');
    }
}
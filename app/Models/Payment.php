<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $primaryKey = 'idPayment';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'createAt';
    const UPDATED_AT = 'updateAt';

    protected $fillable = [
        'idPayment',
        'idUser',
        'idMethod',
        'totalAmount',
        'transactionIdGateway',
        'snapToken',
        'paymentStatus',
        'paymentTime',
        'imageURL',
        'createAt',
        'updateAt',
    ];

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'idMethod', 'idMethod');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'idUser', 'idUser');
    }
}
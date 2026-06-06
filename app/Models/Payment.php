<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['idPayment', 'idMethod', 'transactionIdGateway', 'snapToken', 'paymentStatus', 'paymentTime', 'imageURL'])]
class Payment extends Model
{
    use HasFactory;

    protected $primaryKey = 'idPayment';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'createAt';
    const UPDATED_AT = 'updateAt';

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'idMethod', 'idMethod');
    }
}
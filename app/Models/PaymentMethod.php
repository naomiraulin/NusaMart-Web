<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['idMethod', 'category', 'methodName', 'description', 'provider', 'isActive'])]
class PaymentMethod extends Model
{
    use HasFactory;

    protected $primaryKey = 'idMethod';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'createAt';
    const UPDATED_AT = 'updateAt';
}
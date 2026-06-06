<?php

namespace App\Models;

use Database\Factories\CourierOptionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['idCourier', 'courierName', 'serviceType', 'timeEstimation', 'isActive'])]
class CourierOption extends Model
{
    /** @use HasFactory<CourierOptionFactory> */
    use HasFactory;

    // --- Konfigurasi Custom Tabel Courier Options ---
    protected $primaryKey = 'idCourier';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'createAt';
    const UPDATED_AT = 'updateAt';
    // -----------------------------------------------
}
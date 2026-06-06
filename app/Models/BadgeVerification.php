<?php

namespace App\Models;

use Database\Factories\BadgeVerificationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['idBadge', 'idStore', 'badgeType', 'reviewDate', 'requestDate', 'endDate', 'status', 'notes'])]
class BadgeVerification extends Model
{
    /** @use HasFactory<BadgeVerificationFactory> */
    use HasFactory;

    protected $primaryKey = 'idBadge';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'createAt';
    const UPDATED_AT = 'updateAt';

    /**
     * Relasi Balik ke Model Store
     */
    public function store()
    {
        return $this->belongsTo(Store::class, 'idStore', 'idStore');
    }
}
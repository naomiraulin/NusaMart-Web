<?php

namespace App\Models;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['idCategory', 'categoryName', 'isActive'])]
class Category extends Model
{
    /** @use HasFactory<CategoryFactory> */
    use HasFactory;

    protected $primaryKey = 'idCategory';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'createAt';
    const UPDATED_AT = 'updateAt';
}
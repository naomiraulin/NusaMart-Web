<?php

namespace App\Models;

use Database\Factories\SubCategoryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['idSubCategory', 'idCategory', 'subCategoryName', 'description'])]
class SubCategory extends Model
{
    /** @use HasFactory<SubCategoryFactory> */
    use HasFactory;

    // --- Konfigurasi Custom Tabel Sub Categories ---
    protected $primaryKey = 'idSubCategory';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'createAt';
    const UPDATED_AT = 'updateAt';

    /**
     * Relasi Balik ke Model Category
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'idCategory', 'idCategory');
    }
}
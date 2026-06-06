<?php

namespace App\Models;

use Database\Factories\ProductSubCategoryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['idProductSubCat', 'idProduct', 'idSubCategory'])]
class ProductSubCategory extends Model
{
    /** @use HasFactory<ProductSubCategoryFactory> */
    use HasFactory;

    protected $primaryKey = 'idProductSubCat';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'createAt';
    const UPDATED_AT = 'updateAt';

    // Relasi ke Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'idProduct', 'idProduct');
    }

    // Relasi ke SubCategory
    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'idSubCategory', 'idSubCategory');
    }
}
<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['idProduct', 'idStore', 'productName', 'description', 'weightGram', 'productStatus', 'avgRating', 'sold'])]
class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    protected $primaryKey = 'idProduct';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'createAt';
    const UPDATED_AT = 'updateAt';

    /**
     * Relasie
     */
    public function store()
    {
        return $this->belongsTo(Store::class, 'idStore', 'idStore');
    }

    public function productItems()
    {
        return $this->hasMany(ProductItem::class, 'idProduct', 'idProduct');
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class, 'idProduct', 'idProduct');
    }

    public function subCategories()
    {
        return $this->belongsToMany(
            SubCategory::class,
            'product_sub_categories',
            'idProduct',
            'idSubCategory'
        );
    }
}
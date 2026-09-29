<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $table = 'products';

    protected $appends = ['price'];

    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'description',
        'base_price',
        'discount_price',
        'sku',
        'stock',
        'is_charge_tax',
        'is_featured',
        'status',
        'tags',
        'link',
        'category_id',
        'sub_category_id',
        'created_by',
    ];

    public function getPriceAttribute()
    {
        return $this->discount_price > 0
            ? $this->discount_price
            : $this->base_price;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function galleryImages()
    {
        return $this->hasMany(ProductImage::class)->where('is_primary', 0);
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', 1);
    }

    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function order_products()
    {
        return $this->hasMany(OrderProducts::class, 'order_products_product_id', 'id');
    }
}

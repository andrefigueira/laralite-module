<?php

namespace Modules\Laralite\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'category_id',
        'description',
        'meta',
        'images',
        'price',
    ];

    protected $casts = [
        'meta' => 'object',
        'images' => 'array',
    ];

    public function category()
    {
        return $this->hasOne(ProductCategory::class, 'id', 'category_id');
    }

    public function variant()
    {
        return $this->hasMany(ProductVariant::class, 'product_id', 'id');
    }
}

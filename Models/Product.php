<?php

namespace Modules\Laralite\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'description',
        'meta',
        'images',
        'variants',
        'active',
        'credit_purchasable'
    ];

    protected $casts = [
        'meta' => 'object',
        'images' => 'array',
        'variants' => 'array',
    ];

    public function category()
    {
        return $this->hasOne(ProductCategory::class, 'id', 'category_id');
    }
}

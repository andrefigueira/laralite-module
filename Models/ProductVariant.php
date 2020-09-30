<?php

namespace Modules\Laralite\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'sku',
        'price',
        'sale_price',
        'on_sale',
        'stock',
        'properties',
        'images',
    ];

    protected $casts = [
        'properties' => 'object',
        'images' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}

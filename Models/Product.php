<?php

namespace Modules\Laralite\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'meta',
        'images',
        'price',
    ];
}

<?php

namespace Modules\Laralite\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Eloquent
 */
class ProductCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'active'
    ];
}

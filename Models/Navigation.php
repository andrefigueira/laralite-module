<?php

namespace Modules\Laralite\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Eloquent
 */
class Navigation extends Model
{
    protected $fillable = [
        'name',
        'description',
        'navigation',
    ];

    protected $casts = [
        'navigation' => 'array',
    ];
}

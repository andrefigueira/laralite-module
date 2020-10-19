<?php

namespace Modules\Laralite\Models;

use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'properties',
    ];

    protected $casts = [
        'properties' => 'array',
    ];
}

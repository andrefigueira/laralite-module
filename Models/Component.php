<?php

namespace Modules\Laralite\Models;

use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'settings',
        'properties',
    ];

    protected $casts = [
        'settings' => 'array',
        'properties' => 'array',
    ];
}

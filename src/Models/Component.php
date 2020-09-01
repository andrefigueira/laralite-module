<?php

namespace Modules\Laralite\Models;

use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    protected $fillable = [
        'properties',
    ];

    protected $casts = [
        'properties' => 'array',
    ];
}

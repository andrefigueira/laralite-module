<?php

namespace Modules\Laralite\Models;

use Illuminate\Database\Eloquent\Model;

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

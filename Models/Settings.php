<?php

namespace Modules\Laralite\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $fillable = [
        'id',
        'active',
        'settings',
    ];

    protected $casts = [
        'settings' => 'array',
    ];
}

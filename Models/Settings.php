<?php

namespace Modules\Laralite\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property array|string settings
 * @mixin \Eloquent
 */
class Settings extends Model
{
    protected $fillable = [
        'id',
        'active',
        'settings',
        'colour',
        'site_logo'
    ];

    protected $casts = [
        'settings' => 'array',
    ];
}

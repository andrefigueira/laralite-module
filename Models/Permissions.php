<?php

namespace Modules\Laralite\Models;

use Eloquent;
use Spatie\Permission\Models\Permission;

/**
 * @mixin Eloquent
 */
class Permissions extends Permission
{
    protected $fillable = [
        'name', 'guard_name', 'created_at', 'updated_at'
    ];
}

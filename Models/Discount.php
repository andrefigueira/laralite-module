<?php

namespace Modules\Laralite\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = [
        'name',
        'code',
        'type',
        'value',
    ];
}

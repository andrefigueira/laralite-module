<?php

namespace Modules\Laralite\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriptions extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image',
        'price',
    ];
}

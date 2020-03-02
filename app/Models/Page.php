<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'meta',
        'content',
        'components',
    ];

    protected $casts = [
        'meta' => 'array',
        'content' => 'array',
        'components' => 'array',
    ];
}

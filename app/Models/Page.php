<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'parent_id',
        'template_id',
        'primary',
        'name',
        'slug',
        'meta',
        'content',
        'components',
    ];

    protected $casts = [
        'meta' => 'object',
        'content' => 'array',
        'components' => 'array',
    ];

    public function children()
    {
        return $this->hasMany(Page::class, 'parent_id', 'id')->with('children')->with('template')->with('children')->with('template');
    }

    public function parent()
    {
        return $this->hasOne(Page::class, 'id', 'parent_id');
    }

    public function template()
    {
        return $this->hasOne(Template::class, 'id', 'template_id');
    }
}

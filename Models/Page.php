<?php

namespace Modules\Laralite\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Eloquent
 */
class Page extends Model
{
    protected $fillable = [
        'parent_id',
        'template_id',
        'primary',
        'authentication',
        'anonymousOnly',
        'name',
        'slug',
        'meta',
        'settings',
        'content',
        'components',
    ];

    protected $casts = [
        'meta' => 'object',
        'content' => 'array',
        'components' => 'array',
        'settings' => 'object',
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

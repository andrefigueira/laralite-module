<?php

namespace Modules\Laralite\Models;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $fillable = [
        'module_name',
        'name',
        'description',
        'sections',
        'header_navigation_id',
        'footer_navigation_id',
    ];

    protected $casts = [
        'sections' => 'array',
    ];

    public function headerNavigation()
    {
        return $this->hasOne(Navigation::class, 'id', 'header_navigation_id');
    }

    public function footerNavigation()
    {
        return $this->hasOne(Navigation::class, 'id', 'footer_navigation_id');
    }
}

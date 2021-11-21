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
        'end_date',
        'start_date',
    ];

    public const CUSTOMER_HIDDEN_ATTRIBUTES = [
        'updated_at',
        'created_at',
        'end_date',
        'start_date',
        'id',
        'name'
    ];
}

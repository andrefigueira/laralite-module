<?php

namespace Modules\Laralite\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'unique_id',
        'basket',
        'payment_processor_result',
        'status',
    ];

    protected $casts = [
        'basket' => 'object',
        'payment_processor_result' => 'object',
    ];
}
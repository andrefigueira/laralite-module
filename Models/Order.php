<?php

namespace Modules\Laralite\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'unique_id',
        'customer_id',
        'basket',
        'payment_processor_result',
        'status',
        'order_status',
        'refunded'
    ];

    protected $casts = [
        'basket' => 'object',
        'payment_processor_result' => 'object',
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'order_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
}

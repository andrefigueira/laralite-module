<?php

namespace Modules\Laralite\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Laralite\Models\Customer;
use Modules\Laralite\Models\Order;

class Ticket extends Model
{
    protected $fillable = [
        'unique_id',
        'customer_id',
        'sku',
        'order_id',
        'ticket',
        'validated',
        'admit_quantity',
    ];

    protected $casts = [
        'ticket' => 'object',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}

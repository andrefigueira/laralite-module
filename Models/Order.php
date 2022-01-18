<?php

namespace Modules\Laralite\Models;

use Illuminate\Database\Eloquent\Model;
use stdClass;

/**
 * @property stdClass basket
 * @property string order_status
 * @property bool refunded
 * @property Customer customer
 */
class Order extends Model
{
    protected $fillable = [
        'unique_id',
        'customer_id',
        'basket',
        'payment_processor_result',
        'status',
        'order_status',
        'refunded',
        'confirmation_code'
    ];

    protected $casts = [
        'basket' => 'object',
        'payment_processor_result' => 'object',
    ];
    /**
     * @var mixed
     */
    private $tickets;

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'order_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
}

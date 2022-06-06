<?php

namespace Modules\Laralite\Models;

use Illuminate\Database\Eloquent\Model;

class TicketScans extends Model
{
    protected $fillable = [
        'order_id',
        'ticket_id',
        'customer_id',
        'status'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function ticket(): BelongsTo
{
    return $this->belongsTo(Ticket::class, 'ticket_id');
}

}

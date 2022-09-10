<?php

namespace Modules\Laralite\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use stdClass;

/**
 * @property stdClass basket
 * @property string order_status
 * @property bool refunded
 * @property stdClass|null payment_processor_result
 * @property Customer customer
 * @mixin Eloquent
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
        'confirmation_code',
        'created_at',
        'updated_at',

    ];

    protected $casts = [
        'basket' => 'object',
        'payment_processor_result' => 'object',
    ];

    /**
     * @var mixed
     */
    public $tickets;

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'order_id', 'id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    /**
     * @return null|string
     */
    public function getPaymentId(): ?string
    {
        $paymentResult = $this->getAttribute('payment_processor_result');
        return $paymentResult->id ?? null;
    }
}

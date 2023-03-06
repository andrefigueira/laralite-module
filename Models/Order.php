<?php

namespace Modules\Laralite\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use stdClass;

/**
 * @property int id
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

    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'payable');
    }
    /**
     * @return null|string
     */
    public function getPaymentId(): ?string
    {
        $paymentResult = $this->getAttribute('payment_processor_result');
        return $paymentResult->id ?? null;
    }

    public function getBasket(): array
    {
        return $this->getAttributeValue('basket');
    }
}

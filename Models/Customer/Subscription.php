<?php

namespace Modules\Laralite\Models\Customer;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Laralite\Models\Customer;
use Modules\Laralite\Models\StripeMetaData;
use Modules\Laralite\Models\Subscription as SubscriptionProduct;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Subscription
 * @package Modules\Laralite\Models\Customer
 * @mixin Eloquent
 * @property int id
 * @property string status
 * @property string expiry_date
 * @property int agreed_price
 * @property int customer_id
 * @property int subscription_id
 */
class Subscription extends Model
{
    use StripeMetaData;

    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_INACTIVE = 'INACTIVE';
    const STATUS_CANCELED = 'CANCELED';
    const STATUS_EXPIRED = 'EXPIRED';
    const STATUS_PAYMENT_DUE = 'PAYMENT_DUE';
    const STATUS_PAYMENT_DECLINED = 'PAYMENT_DECLINED';
    const STATUS_PENDING_PAYMENT = 'PENDING_PAYMENT';
    const STATUS_DISABLED = 'DISABLED';

    /**
     * @var string
     */
    protected $table = 'customer_subscriptions';

    protected $fillable = [
        'customer_id',
        'price_id',
        'expiry_date',
        'status',
        'meta_data',
        'agreed_price'
    ];

    protected $attributes = [
        'status' => self::STATUS_INACTIVE
    ];

    protected $casts = [
        'meta_data' => 'array'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function subscriptionPrice(): HasOne
    {
        return $this->hasOne(SubscriptionProduct\Price::class, 'id', 'price_id');
    }
}
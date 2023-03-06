<?php

namespace Modules\Laralite\Models\Customer;

use DateTime;
use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Modules\Laralite\Models\Customer;
use Modules\Laralite\Models\Payment;
use Modules\Laralite\Models\StripeMetaData;
use Modules\Laralite\Models\Subscription as SubscriptionProduct;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Subscription
 * @package Modules\Laralite\Models\Customer
 * @mixin Eloquent
 * @property int id
 * @property string status
 * @property string|DateTime expiry_date
 * @property string|DateTime start_date
 * @property int agreed_price
 * @property int customer_id
 * @property int subscription_id
 */
class Subscription extends Model
{
    use StripeMetaData;

    public const STATUS_ACTIVE = 'ACTIVE';
    public const STATUS_INACTIVE = 'INACTIVE';
    public const STATUS_CANCELED = 'CANCELED';
    public const STATUS_EXPIRED = 'EXPIRED';
    public const STATUS_PAYMENT_DUE = 'PAYMENT_DUE';
    public const STATUS_PAYMENT_DECLINED = 'PAYMENT_DECLINED';
    public const STATUS_PENDING_PAYMENT = 'PENDING_PAYMENT';
    public const STATUS_DISABLED = 'DISABLED';

    /**
     * @var string
     */
    protected $table = 'customer_subscriptions';

    protected $fillable = [
        'customer_id',
        'price_id',
        'unique_id',
        'subscription_id',
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

    public function subscription(): HasOne
    {
        return $this->hasOne(SubscriptionProduct::class, 'id', 'subscription_id');
    }

    public function subscriptionPrice(): HasOne
    {
        return $this->hasOne(SubscriptionProduct\Price::class, 'id', 'price_id');
    }

    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'payable');
    }
}
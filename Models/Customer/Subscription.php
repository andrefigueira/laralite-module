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
 * @property int customer_id
 * @property int subscription_id
 */
class Subscription extends Model
{
    use StripeMetaData;

    /**
     * @var string
     */
    protected $table = 'customer_subscriptions';

    protected $fillable = [
        'customer_id',
        'subscription_id',
        'expiry_date',
        'status',
        'meta_data',
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
}
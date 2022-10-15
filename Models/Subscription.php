<?php

namespace Modules\Laralite\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Modules\Laralite\Models\Subscription\Price;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Laralite\Models\Customer\Subscription as CustomerSubscription;

/**
 * Class Subscription
 * @package Modules\Laralite\Models
 * @property string $name
 * @property string $description
 * @property Price $price
 * @property string $recurring_period;
 * @mixin Eloquent
 */
class Subscription extends Model
{
    use StripeMetaData;

    protected $fillable = [
        'name',
        'description',
        'image',
        'price',
        'meta_data',
        'recurring_period',
        'default_credit_amount',
        'default_initial_credit_amount'
    ];

    protected $casts = [
        'meta_data' => 'array'
    ];

    public function prices(): HasMany
    {
        return $this->hasMany(Price::class, 'subscription_id');
    }

    public function customerSubscription(): HasMany
    {
        return $this->hasMany(CustomerSubscription::class, 'subscription_id');
    }
}

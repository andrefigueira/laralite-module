<?php

namespace Modules\Laralite\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Modules\Laralite\Models\Customer\Wallet;
use Modules\Laralite\Models\Subscription\Price;
use Modules\Laralite\Notifications\ResetPasswordNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Modules\Laralite\Models\Customer\Subscription as CustomerSubscription;

/**
 * Class Customer
 * @property string email
 * @property string name
 * @package Modules\Laralite\Models
 * @mixin Eloquent
 */
class Customer extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    use StripeMetaData;

    protected $casts = [
        'newsletter_subscription' => 'array',
        'numbers' => 'array',
        'email_verified_at' => 'datetime',
        'meta_data' => 'array'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $fillable = [
        'unique_id',
        'name',
        'email',
        'password',
        'newsletter_subscription',
        'newsletter_subscription->email',
        'newsletter_subscription->sms',
        'newsletter_subscription->phone',
        'numbers',
        'numbers->mobile',
        'meta_data',
        'profile_image'
    ];

    protected $attributes = [
        'newsletter_subscription' => '{
            "email": false,
            "sms": false,
            "phone": false
        }',
        'numbers' => '{
            "mobile": null
        }'
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'customer_id', 'id')
            ->orderBy('created_at', 'desc');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'customer_id', 'id');
    }

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class, 'customer_id', 'id');
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(CustomerSubscription::class, 'customer_id', 'id')
            ->orderBy('created_at', 'desc');
    }

    public function subscriptionPrice(): HasMany
    {
        return $this->hasMany(Price::class, 'price_id', 'id')
            ->orderBy('created_at', 'desc');
    }

}

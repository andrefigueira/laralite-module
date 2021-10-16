<?php

namespace Modules\Laralite\Models;

use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class Customer
 * @package Modules\Laralite\Models
 */
class Customer extends Authenticatable
{
    use Notifiable;

    protected $casts = [
        'newsletter_subscription' => 'array',
        'email_verified_at' => 'datetime',
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
        'newsletter_subscription->phone'
    ];

    protected $attributes = [
        'newsletter_subscription' => '{
            "email": false,
            "sms": false,
            "phone": false
        }'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id', 'id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'customer_id', 'id');
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier() {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims() {
        return [];
    }
}

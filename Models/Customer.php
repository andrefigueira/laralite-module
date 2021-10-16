<?php

namespace Modules\Laralite\Models;

use Illuminate\Notifications\Notifiable;
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
}

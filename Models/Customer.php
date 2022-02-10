<?php

namespace Modules\Laralite\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class Customer
 * @property string email
 * @package Modules\Laralite\Models
 */
class Customer extends Authenticatable
{
    use Notifiable;

    protected $casts = [
        'newsletter_subscription' => 'array',
        'numbers' => 'array',
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
        'newsletter_subscription->phone',
        'numbers',
        'numbers->mobile'
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

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id', 'id')->orderBy('created_at', 'desc');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'customer_id', 'id');
    }
}

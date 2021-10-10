<?php

namespace Modules\Laralite\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\Laralite\Entities\Ticket;

/**
 * Class Customer
 * @package Modules\Laralite\Models
 */
class Customer extends Model
{
    protected $casts = [
        'newsletter_subscription' => 'array'
    ];

    protected $fillable = [
        'unique_id',
        'name',
        'email',
        'password',
        'registered',
        'newsletter_subscription->email'
    ];

    protected $attributes = [
        'newsletter_subscription' => '{
            "email": false
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

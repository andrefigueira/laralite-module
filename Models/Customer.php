<?php

namespace Modules\Laralite\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Laralite\Entities\Ticket;

class Customer extends Model
{
    protected $fillable = [
        'unique_id',
        'name',
        'email',
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

<?php

namespace Modules\Laralite\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerWallet extends Model
{
    protected $fillable = [
        'customer_id',
        'balance',
    ];
}

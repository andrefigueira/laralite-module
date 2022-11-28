<?php

namespace Modules\Laralite\Models\Customer;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Laralite\Models\Customer;

/**
 * @mixin Eloquent
 * @property int id
 * @property int balance
 */
class Wallet extends Model
{
    protected $table = 'customer_wallets';

    protected $fillable = [
        'customer_id',
        'balance',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}

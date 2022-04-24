<?php

namespace Modules\Laralite\Models\Subscription;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Modules\Laralite\Models\StripeMetaData;
use Modules\Laralite\Models\Subscription;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Price
 * @package Modules\Laralite\Models\Subscription
 * @mixin Eloquent
 * @property int id
 * @property int price
 * @property string recurring_period
 */
class Price extends Model
{
    use StripeMetaData;

    protected $table = 'subscription_prices';

    protected $fillable = [
        'name',
        'price',
        'meta_data',
        'recurring_period',
    ];

    protected $casts = [
        'meta_data' => 'array'
    ];

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }
}
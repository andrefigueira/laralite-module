<?php

namespace Modules\Laralite\Models\Subscription;

use Illuminate\Database\Eloquent\Model;
use Modules\Laralite\Models\Subscription;

class Price extends Model
{
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

    public function subscription(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }

    public function getMetaData(): array
    {
        $metaData = $this->getAttribute('meta_data') ?: [];
        if ($metaData) {
            $metaData = is_string($metaData) ? json_decode($metaData) : $metaData;
        }

        return $metaData;
    }

    public function getStripePriceId(): string
    {
        $metaData = $this->getMetaData();

        return $metaData['stripe']['price_id'] ?? '';
    }

    public function setStripePriceId($stripeId): Price
    {
        $metaData = $this->getMetaData();
        $metaData['stripe']['price_id'] = $stripeId;
        $this->setAttribute('meta_data', $metaData);

        return $this;
    }
}
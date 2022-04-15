<?php

namespace Modules\Laralite\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Laralite\Models\Subscription\Price;

class Subscription extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image',
        'price',
        'meta_data',
        'recurring_period'
    ];

    protected $casts = [
        'meta_data' => 'array'
    ];

    public function prices(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Price::class, 'subscription_id');
    }

    public function getMetaData(): array
    {
        $metaData = $this->getAttribute('meta_data') ?: [];
        if ($metaData) {
            $metaData = is_string($metaData) ? json_decode($metaData) : $metaData;
        }

        return $metaData;
    }

    public function getStripeProductId(): string
    {
        $metaData = $this->getMetaData();

        return $metaData['stripe']['product_id'] ?? '';
    }

    public function setStripeProductId($stripeId): Subscription
    {
        $metaData = $this->getMetaData();
        $metaData['stripe']['product_id'] = $stripeId;
        $this->setAttribute('meta_data', $metaData);

        return $this;
    }
}

<?php

namespace Modules\Laralite\Services\StripeService;

use Stripe\StripeClient;

/**
 * Trait Charge
 * @package Modules\Laralite\Services\StripeService
 * @property StripeClient client
 */
trait Charge
{
    public function searchCharges(array $query): ApiResourceWrapper
    {
        return $this->getApiResourceWrapper($this->client->charges->search($query));
    }
}
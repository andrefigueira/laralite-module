<?php

namespace Modules\Laralite\Services\StripeService;

use Stripe\StripeClient;

/**
 * Trait Transfer
 * @package Modules\Laralite\Services\StripeService
 * @property StripeClient client
 */
trait Transfer
{
    public function updateTransfer(string $id, array $payload): ApiResourceWrapper
    {
        return $this->getApiResourceWrapper($this->client->transfers->update($id, $payload));
    }

    public function searchTransfers(array $query): ApiResourceWrapper
    {
        return $this->getApiResourceWrapper($this->client->transfers->all($query));
    }
}
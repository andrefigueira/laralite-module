<?php

namespace Modules\Laralite\Services\StripeService;

use Modules\Laralite\Exceptions\AppException;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

trait Price
{
    public function savePrice($payload, ?string $id = null): ApiResourceWrapper
    {
        if ($id) {
            // We cannot delete a Stripe Price so we disabled and create a new one
            $this->client->prices->update($id, ['active' => false]);
        }
        return $this->getApiResourceWrapper($this->client->prices->create($payload));
    }

    public function getPrice($id)
    {
        return $this->getApiResourceWrapper($this->client->prices->retrieve($id));
    }
}
<?php

namespace Modules\Laralite\Services\StripeService;

trait Customer
{
    public function saveCustomer(array $payload, ?string $id = null): ApiResourceWrapper
    {
        if ($id) {
            return $this->getApiResourceWrapper($this->client->customers->update($id, $payload));
        }
        return $this->getApiResourceWrapper($this->client->customers->create($payload));
    }
}
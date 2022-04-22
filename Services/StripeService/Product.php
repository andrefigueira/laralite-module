<?php

namespace Modules\Laralite\Services\StripeService;

/**
 * Trait Product
 * @package Modules\Laralite\Services\StripeService
 *
 */
trait Product
{
    public function saveProduct(array $payload, ?string $id = null): ApiResourceWrapper
    {
        if ($id) {
            return $this->getApiResourceWrapper($this->client->products->update($id, $payload));
        }
        return $this->getApiResourceWrapper($this->client->products->create($payload));
    }
}
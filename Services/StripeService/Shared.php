<?php

namespace Modules\Laralite\Services\StripeService;

use Stripe\ApiResource;
use Stripe\StripeClient;
use Stripe\StripeObject;

trait Shared
{
    /**
     * @var StripeClient
     */
    protected $client;

    protected function getApiResourceWrapper(StripeObject $apiResource): ApiResourceWrapper
    {
        return new ApiResourceWrapper($apiResource);
    }
}
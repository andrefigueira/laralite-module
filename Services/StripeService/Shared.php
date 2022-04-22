<?php

namespace Modules\Laralite\Services\StripeService;

use Stripe\ApiResource;
use Stripe\StripeClient;

trait Shared
{
    /**
     * @var StripeClient
     */
    protected $client;

    protected function getApiResourceWrapper(ApiResource $apiResource): ApiResourceWrapper
    {
        return new ApiResourceWrapper($apiResource);
    }
}
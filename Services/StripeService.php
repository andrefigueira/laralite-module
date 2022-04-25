<?php

namespace Modules\Laralite\Services;

use Modules\Laralite\Services\StripeService\Customer;
use Modules\Laralite\Services\StripeService\Price;
use Modules\Laralite\Services\StripeService\Product;
use Modules\Laralite\Services\StripeService\Shared;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentMethod;
use Stripe\StripeClient;

class StripeService
{
    use Price;
    use Customer;
    use Product;
    use Shared;

    /**
     * @var StripeClient
     */
    protected $client;

    public function __construct(StripeClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param array $payload
     * @return StripeService\ApiResourceWrapper
     * @throws ApiErrorException
     */
    public function createPaymentIntent(array $payload): StripeService\ApiResourceWrapper
    {
        return $this->getApiResourceWrapper($this->client->paymentIntents->create($payload));
    }

    /**
     * @param string $id
     * @param $payload
     * @return StripeService\ApiResourceWrapper
     * @throws ApiErrorException
     */
    public function confirmPaymentIntent(string $id, $payload): StripeService\ApiResourceWrapper
    {
        return $this->getApiResourceWrapper($this->client->paymentIntents->confirm($id, $payload));
    }

    /**
     * @param array $payload
     * @return StripeService\ApiResourceWrapper
     * @throws ApiErrorException
     */
    public function createSubscription(array $payload): StripeService\ApiResourceWrapper
    {
        return $this->getApiResourceWrapper($this->client->subscriptions->create($payload));
    }

    /**
     * @param string $id
     * @param array|null $payload
     * @return StripeService\ApiResourceWrapper
     * @throws ApiErrorException
     */
    public function cancelSubscription(string $id, ?array $payload = null): StripeService\ApiResourceWrapper
    {
        return $this->getApiResourceWrapper($this->client->subscriptions->cancel($id, $payload));
    }

    /**
     * @param string $id
     * @param array $payload
     * @return StripeService\ApiResourceWrapper
     * @throws ApiErrorException
     */
    public function updateSubscription(string $id, array $payload): StripeService\ApiResourceWrapper
    {
        return $this->getApiResourceWrapper($this->client->subscriptions->update($id, $payload));
    }

    /**
     * @param string $id
     * @return StripeService\ApiResourceWrapper
     * @throws ApiErrorException
     */
    public function getSubscription(string $id): StripeService\ApiResourceWrapper
    {
        return $this->getApiResourceWrapper($this->client->subscriptions->retrieve($id));
    }

    /**
     * @param string $id
     * @return StripeService\ApiResourceWrapper
     * @throws ApiErrorException
     */
    public function getPaymentIntent(string $id): StripeService\ApiResourceWrapper
    {
        return $this->getApiResourceWrapper($this->client->paymentIntents->retrieve($id));
    }

    public function getPaymentMethod(string $id, $params = null): StripeService\ApiResourceWrapper
    {
        return $this->getApiResourceWrapper($this->client->paymentMethods->retrieve($id, $params));
    }
}
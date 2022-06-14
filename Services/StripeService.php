<?php

namespace Modules\Laralite\Services;

use Modules\Laralite\Services\StripeService\Charge;
use Modules\Laralite\Services\StripeService\Connected;
use Modules\Laralite\Services\StripeService\Customer;
use Modules\Laralite\Services\StripeService\Price;
use Modules\Laralite\Services\StripeService\Product;
use Modules\Laralite\Services\StripeService\Shared;
use Modules\Laralite\Services\StripeService\Transfer;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

class StripeService
{
    use Price;
    use Customer;
    use Product;
    use Transfer;
    use Connected;
    use Charge;
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
     * @param array|null $options
     * @return StripeService\ApiResourceWrapper
     * @throws ApiErrorException
     */
    public function createPaymentIntent(array $payload, ?array $options = null): StripeService\ApiResourceWrapper
    {
        return $this->getApiResourceWrapper($this->client->paymentIntents->create($payload, $options));
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

    public function refund($id, $payload = []): StripeService\ApiResourceWrapper
    {
        $type = $this->getPaymentIdType($id);
        $payload[$type] = $id;
        return $this->getApiResourceWrapper($this->client->refunds->create($payload));
    }

    private function getPaymentIdType($id)
    {
        $paymentType = substr($id, 0, 3);

        switch ($paymentType) {
            case 'ch_':
                return 'charge';
            case 'pi_':
                return 'payment_intent';
            default:
                throw new \Exception('invalid payment ID');
        }
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
    public function getCustomer(string $id): StripeService\ApiResourceWrapper
    {
        return $this->getApiResourceWrapper($this->client->customers->retrieve($id));
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

    /**
     * @param string $id
     * @param array $payload
     * @return StripeService\ApiResourceWrapper
     * @throws ApiErrorException
     */
    public function updatePaymentIntent(string $id, array $payload = []): StripeService\ApiResourceWrapper
    {
        return $this->getApiResourceWrapper($this->client->paymentIntents->update($id, $payload));
    }

    /**
     * @param string $id
     * @param null $params
     * @return StripeService\ApiResourceWrapper
     * @throws ApiErrorException
     */
    public function getPaymentMethod(string $id, $params = null): StripeService\ApiResourceWrapper
    {
        return $this->getApiResourceWrapper($this->client->paymentMethods->retrieve($id, $params));
    }
}
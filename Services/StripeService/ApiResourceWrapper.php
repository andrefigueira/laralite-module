<?php


namespace Modules\Laralite\Services\StripeService;


use Modules\Laralite\Exceptions\AppException;
use Stripe\ApiResource;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use Stripe\StripeObject;
use Stripe\Subscription;

class ApiResourceWrapper
{
    protected $apiResource;

    public function __construct(StripeObject $apiResource)
    {
        $this->apiResource = $apiResource;
    }

    public function getApiResource(): StripeObject
    {
        return $this->apiResource;
    }

    public function get($key)
    {
        $path = explode('/', $key);
        $value = $this->apiResource;
        foreach ($path as $key) {
            $value = $value->$key;
        }

        return $value;
    }

    public function getTimeToDate($key)
    {
        $time = $this->apiResource->offsetGet($key);
        $date = new \DateTime();
        $date->setTimestamp($time);
        return $date;

    }

    public function deleteSubscription()
    {
        if (!$this->apiResource instanceof Subscription) {
            throw new \Exception('Invalid method call!');
        }
        $this->apiResource->delete();
    }

    /**
     * @return bool
     * @throws AppException
     */
    public function paymentCompleted(): bool
    {
        if (!$this->apiResource instanceof PaymentIntent) {
            throw new AppException('Invalid method call!');
        }
        return ($this->apiResource->status === PaymentIntent::STATUS_SUCCEEDED);
    }

    /**
     * @return $this
     * @throws AppException
     * @throws ApiErrorException
     */
    public function confirmPayment(): ApiResourceWrapper
    {
        if (!$this->apiResource instanceof PaymentIntent) {
            throw new AppException('Invalid method call!');
        }
        $this->apiResource->confirm();

        return $this;
    }

    /**
     * @return bool
     * @throws AppException
     */
    public function requiresPayment(): bool
    {
        if (!$this->apiResource instanceof PaymentIntent) {
            throw new AppException('Invalid method call!');
        }

        return ($this->apiResource->status !== PaymentIntent::STATUS_SUCCEEDED &&
            $this->apiResource->status !== PaymentIntent::STATUS_CANCELED);
    }

    public function toArray(): array
    {
        return $this->apiResource->toArray();
    }

    public function getPaymentMethodDetails(): array
    {
        if (!$this->apiResource instanceof PaymentMethod) {
            return [];
        }

        return [
            'id' => $this->get('id'),
            'cardBrand' => $this->get('card/brand'),
            'last4Digits' => $this->get('card/last4'),
            'country' => $this->get('card/country')
        ];
    }

    public function __toString()
    {
        return json_encode($this->toArray());
    }
}
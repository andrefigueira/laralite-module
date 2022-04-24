<?php


namespace Modules\Laralite\Services\StripeService;


use Modules\Laralite\Exceptions\AppException;
use Stripe\ApiResource;
use Stripe\PaymentIntent;
use Stripe\Subscription;

class ApiResourceWrapper
{
    protected $apiResource;

    public function __construct(ApiResource $apiResource)
    {
        $this->apiResource = $apiResource;
    }

    public function get($key)
    {
        return $this->apiResource->offsetGet($key);
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
     * @param $path
     * @return mixed
     */
    public function getNested($path): ApiResource
    {
        $path = explode('/', $path);
        $value = $this->apiResource;
        foreach ($path as $key) {
            $value = $value->$key;
        }

        return $value;
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
}
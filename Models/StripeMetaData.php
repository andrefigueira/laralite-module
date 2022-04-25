<?php


namespace Modules\Laralite\Models;


trait StripeMetaData
{
    use MetaData;

    public function setStripeMetaData($key, $value): self
    {

        $metaData = $this->getMetaData();
        $metaData['stripe'][$key] = $value;
        $this->setAttribute('meta_data', $metaData);

        return $this;
    }

    /**
     * @param null $key
     * @return array|string|int
     */
    public function getStripeMetaData($key = null)
    {
        if (!$key) {
            return $this->getMetaData()['stripe'] ?? [];
        }

        if (strpos($key, '/')) {
            $path = explode('/', $key);
            return $this->getParts($path, $this->getMetaData()['stripe']);
        }

        return $this->getMetaData()['stripe'][$key] ?? null;
    }

    protected function getParts(array $path, $value)
    {
        foreach ($path as $key) {
            $value = $value[$key];
        }

        return $value;
    }

    public function getStripeCustomerId(): ?string
    {
        return $this->getStripeMetaData('customer_id');
    }

    public function setStripeCustomerId(?string $id = null)
    {
        $this->setStripeMetaData('customer_id', $id);
    }

    public function getStripePriceId(): ?string
    {
        return $this->getStripeMetaData('price_id');
    }

    public function setStripePriceId(?string $id = null)
    {
        $this->setStripeMetaData('price_id', $id);
    }

    public function getStripeProductId(): ?string
    {
        return $this->getStripeMetaData('product_id');
    }

    public function setStripeProductId(?string $id = null)
    {
        $this->setStripeMetaData('product_id', $id);
    }

    public function getStripeSubscriptionId(): ?string
    {
        return $this->getStripeMetaData('subscription_id');
    }

    public function setStripeSubscriptionId(?string $id = null)
    {
        $this->setStripeMetaData('subscription_id', $id);
    }

    public function getStripeSubscriptionEndPeriod(): ?string
    {
        return $this->getStripeMetaData('subscription_end_period');
    }

    public function setStripeSubscriptionEndPeriod(?int $id = null)
    {
        $this->setStripeMetaData('subscription_end_period', $id);
    }

    public function getStripePaymentIntentId()
    {
        return $this->getStripeMetaData('payment_intent_id');
    }

    public function setStripePaymentIntentId(?string $id = null)
    {
        $this->setStripeMetaData('payment_intent_id', $id);
    }

    public function getStripePaymentMethodId()
    {
        return $this->getStripeMetaData('payment_method_id');
    }

    public function setStripePaymentMethodId(?string $id = null)
    {
        $this->setStripeMetaData('payment_method_id', $id);
    }

    public function setStripePaymentMethodDetails(?array $details = null)
    {
        $this->setStripeMetaData('payment_method_details', $details);
    }

    public function getPaymentMethodDetails()
    {
        return $this->getStripeMetaData('payment_method_details');
    }
}
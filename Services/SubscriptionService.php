<?php

namespace Modules\Laralite\Services;

use Modules\Laralite\Models\Subscription as SubscriptionModel;
use Modules\Laralite\Models\Subscription\Price as PriceModel;
use Modules\Laralite\Services\StripeService\ApiResourceWrapper;

class SubscriptionService
{
    /**
     * @var SettingsService
     */
    protected $settingsService;

    /**
     * @var StripeService
     */
    protected $stripeService;

    public function __construct(SettingsService $settingsService, StripeService $stripeService)
    {
        $this->settingsService = $settingsService;
        $this->stripeService = $stripeService;
    }

    /**
     * @param array $subscriptionArray
     * @return SubscriptionModel
     * @throws \Throwable
     */
    public function save(array $subscriptionArray): SubscriptionModel
    {
        $update = false;
        $price = $subscriptionArray['price'] ?? null;
        unset($subscriptionArray['price']);
        if (empty($subscriptionArray['id'])) {
            /** @var SubscriptionModel $subscription */
            $subscription = SubscriptionModel::create($subscriptionArray);

            /** @var PriceModel $priceModel */
            $priceModel = $subscription->prices()->create([
                'name' => $subscriptionArray['name'],
                'price' => $price,
                'recurring_period' => 'month',
            ]);
        } else {
            /** @var SubscriptionModel $subscription */
            $subscription = SubscriptionModel::find($subscriptionArray['id']);
            $subscription->update($subscriptionArray);
            $priceModel = $subscription->prices()->getResults()->first() ?: $subscription->prices()->create([
                'name' => $subscriptionArray['name'],
                'price' => $price,
                'recurring_period' => 'month',
            ]);
            $priceModel->price = $price;
            $update = true;
        }

        $stripeProduct = $this->saveStripeProduct($subscription, $update);
        $stripePrice = ($update && $priceModel->getStripePriceId())
            ? $this->stripeService->getPrice($priceModel->getStripePriceId())
            : null;
        if (
            !$update ||
            ($stripePrice === null) ||
            ((int)$priceModel->price !== (int)$stripePrice->get('unit_amount'))
        ) {
            $stripePrice = $this->saveStripePrice($priceModel, $stripeProduct, $update);
        }

        $priceModel->setStripePriceId($stripePrice->get('id'));
        if (!$update || $stripeProduct->get('id') !== $subscription->getStripeProductId()) {
            $subscription->setStripeProductId($stripeProduct->get('id'));
        }
        $priceModel->save();
        $subscription->save();

        return $subscription;
    }

    public function deleteSubscription(SubscriptionModel $subscription)
    {
        $subscription->delete();
    }

    protected function saveStripeProduct(SubscriptionModel $subscription, bool $update = false): ApiResourceWrapper
    {
        try {
            $payload = [
                'name' => $subscription->name,
                'description' => $subscription->description,
                'metadata' => [
                    'external_id' => $subscription->id,
                ],
            ];
            return $this->stripeService->saveProduct($payload, $subscription->getStripeProductId());
        } catch (\Throwable $exception) {
            \Log::error('Failed to create stripe subscription product', [
                'message' => $exception->getMessage(),
            ]);
            throw $exception;
        }
    }

    protected function saveStripePrice(
        PriceModel $priceModel,
        ApiResourceWrapper $stripeProduct,
        bool $update = false
    ): ApiResourceWrapper
    {
        try {
            $currency = $this->settingsService->getCurrency()['value'] ?: 'usd';
            $currency = strtolower($currency);
            $payload = [
                'product' => $stripeProduct->get('id'),
                'unit_amount' => $priceModel->price,
                'metadata' => [
                    'external_id' => $priceModel->id,
                ],
                'currency' => $currency,
                'recurring' => [
                    'interval' => 'month',
                ],
            ];
            return $this->stripeService->savePrice($payload, $priceModel->getStripePriceId());
        } catch (\Throwable $exception) {
            \Log::error('Failed to create stripe product price', [
                'message' => $exception->getMessage(),
            ]);
            throw $exception;
        }
    }
}
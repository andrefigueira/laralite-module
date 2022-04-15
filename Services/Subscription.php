<?php

namespace Modules\Laralite\Services;

use Modules\Laralite\Models\Subscription as SubscriptionModel;
use Modules\Laralite\Models\Subscription\Price as PriceModel;
use Stripe\Price;
use Stripe\Product;
use Stripe\StripeClient;

class Subscription
{
    /**
     * @var SettingsService
     */
    protected $settingsService;

    /**
     * @var StripeClient
     */
    protected $stripeClient;

    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
        $stripeKey = $this->settingsService->getSettingsArray()['stripeSecretKey'] ?? null;
        $this->stripeClient = new StripeClient($stripeKey);
    }

    /**
     * @param array $subscriptionArray
     * @return SubscriptionModel
     * @throws \Throwable
     */
    public function save(array $subscriptionArray): SubscriptionModel
    {
        $update = false;
        if (empty($subscriptionArray['id'])) {
            /** @var SubscriptionModel $subscription */
            $subscription = SubscriptionModel::create([
                'name' => $subscriptionArray['name'],
                'description' => $subscriptionArray['description'],
                'image' => $subscriptionArray['image'],
            ]);

            /** @var PriceModel $priceModel */
            $priceModel = $subscription->prices()->create([
                'name' => $subscriptionArray['name'],
                'price' => $subscriptionArray['price'],
                'recurring_period' => 'month',
            ]);
        } else {
            /** @var SubscriptionModel $subscription */
            $subscription = SubscriptionModel::find($subscriptionArray['id']);
            $subscription->update([
                'name' => $subscriptionArray['name'],
                'description' => $subscriptionArray['description'],
                'image' => $subscriptionArray['image'],
            ]);
            $priceModel = $subscription->prices()->getResults()->first();
            $priceModel->price = $subscriptionArray['price'];
            $update = true;
        }

        $stripeProduct = $this->saveStripeProduct($subscription, $update);
        $stripePrice = $update ? $this->stripeClient->prices->retrieve($priceModel->getStripePriceId()) : null;
        if (!$update || ($stripePrice && (int)$priceModel->price !== (int)$stripePrice->unit_amount)) {
            $stripePrice = $this->saveStripePrice($priceModel, $stripeProduct, $update);
        }
        $priceModel->setStripePriceId($stripePrice->id);
        if (!$update) {
            $subscription->setStripeProductId($stripeProduct->id);
        }
        $priceModel->save();
        $subscription->save();


        return $subscription;
    }

    protected function saveStripeProduct(SubscriptionModel $subscription, bool $update = false): Product
    {
        try {
            $payload = [
                'name' => $subscription->name,
                'description' => $subscription->description,
                'metadata' => [
                    'external_id' => $subscription->id,
                ]
            ];
            if ($update) {
                return $this->stripeClient->products->update($subscription->getStripeProductId(), $payload);
            }
            return $this->stripeClient->products->create($payload);
        } catch (\Throwable $exception) {
            \Log::error('Failed to create stripe subscription product', [
                'message' => $exception->getMessage(),
            ]);
            throw $exception;
        }
    }

    protected function saveStripePrice(PriceModel $priceModel, Product $stripeProduct, bool $update = false): Price
    {
        try {
            $currency = $this->settingsService->getCurrency()['value'] ?: 'usd';
            $currency = strtolower($currency);
            $payload = [
                'product' => $stripeProduct->id,
                'unit_amount' => $priceModel['price'],
                'metadata' => [
                    'external_id' => $priceModel->id,
                ],
                'currency' => $currency,
                'recurring' => [
                    'interval' => 'month'
                ]
            ];
            if ($update) {
                $this->stripeClient->prices->update($priceModel->getStripePriceId(), ['active' => false]);
            }
            return $this->stripeClient->prices->create($payload);
        } catch (\Throwable $exception) {
            \Log::error('Failed to create stripe product price', [
                'message' => $exception->getMessage(),
            ]);
            throw $exception;
        }
    }
}
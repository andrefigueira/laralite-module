<?php

namespace Modules\Laralite\Services;

use Modules\Laralite\Models\Subscription as SubscriptionModel;
use Modules\Laralite\Models\Subscription\Price as PriceModel;

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
        $price = $subscriptionArray['price'] ?? null;
        unset($subscriptionArray['price']);
        if (empty($subscriptionArray['id'])) {
            /** @var SubscriptionModel $subscription */
            $subscription = SubscriptionModel::create($subscriptionArray);

            /** @var PriceModel $priceModel */
            $subscription->prices()->create([
                'name' => $subscriptionArray['name'],
                'price' => $price,
                'recurring_period' => 'year',
            ]);
        } else {
            /** @var SubscriptionModel $subscription */
            $subscription = SubscriptionModel::find($subscriptionArray['id']);
            $subscription->update($subscriptionArray);
            $priceModel = $subscription->prices()->getResults()->first() ?: $subscription->prices()->create([
                'name' => $subscriptionArray['name'],
                'price' => $price,
                'recurring_period' => 'year',
            ]);
            $priceModel->price = $price;
            $priceModel->save();
            $subscription->save();
        }

        return $subscription;
    }

    public function deleteSubscription(SubscriptionModel $subscription)
    {
        $subscription->delete();
    }
}
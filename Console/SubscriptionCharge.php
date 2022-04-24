<?php

namespace Modules\Laralite\Console;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Modules\Laralite\Models\Customer;
use Modules\Laralite\Models\Customer\Subscription;
use Modules\Laralite\Models\Subscription\Price;
use Modules\Laralite\Services\SettingsService;
use Modules\Laralite\Services\StripeService;

class SubscriptionCharge extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laralite:subscription-charge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start charging subscription that have reached there end date';

    /**
     * @var StripeService
     */
    private $stripeService;

    /**
     * @var SettingsService
     */
    private $settingsService;

    /**
     * Create a new command instance.
     *
     * @param StripeService $stripeService
     */
    public function __construct(StripeService $stripeService, SettingsService $settingsService)
    {
        $this->stripeService = $stripeService;
        $this->settingsService = $settingsService;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Get subscriptions to charge....');

        $currency = $this->settingsService->getCurrency();
        /** @var Subscription $subscription */
        $subscriptions = Subscription::whereIn(
            'status' ,
            [Subscription::STATUS_ACTIVE, Subscription::STATUS_EXPIRED, Subscription::STATUS_PAYMENT_DUE]
        )->whereDate('expiry_date', '<=', Carbon::now())->get();

        if (!$subscriptions->count()) {
            $this->info('No expired subscriptions found to charge.');
            return;
        }

        foreach ($subscriptions as $subscription) {
            /** @var Customer $customer */
            $customer = $subscription->customer()->first();
            /** @var Price $plan */
            $price = $subscription->subscriptionPrice()->first();

            if (!$agreedPrice = $subscription->getAttributeValue('agreed_price')) {
                $m = 'Subscription `' . $subscription->id . '`  agreed price not set when it should be.';
                $this->info($m);
                \Log::alert($m);
            }

            $payment_intent = $this->stripeService->createPaymentIntent([
                'amount' => $agreedPrice ?? $price->price,
                'currency' => strtolower($currency['value']),
                'customer' => $customer->getStripeCustomerId(),
                'payment_method' => $subscription->getStripePaymentMethodId(),
                'off_session' => true,
                'confirm' => true,
            ]);

            $subscription->expiry_date = new \DateTime('+ 1' . $price->recurring_period);
            $subscription->status = 'ACTIVE';
            $subscription->save();

            \Log::info('Subscription Charged and updated successfully', [
                'payment_intent' => $payment_intent->toArray(),
                'subscription' => $subscription->toArray(),
                'price' => $price->toArray(),
            ]);
        }


        $this->info('All expired Subscriptions have been processed successfully');
    }
}

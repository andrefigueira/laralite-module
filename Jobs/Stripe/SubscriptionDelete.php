<?php

namespace Modules\Laralite\Jobs\Stripe;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Laralite\Models\Customer\Subscription;
use Modules\Laralite\Services\StripeService;
use Stripe\Event;
use Stripe\Exception\ApiErrorException;

class SubscriptionDelete implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var integer|null
     */
    private $subscriptionId;

    /**
     * @var string
     */
    private $stripeSubscriptionId;

    /**
     * Create a new job instance.
     *
     * @param string $stripeSubscriptionId
     * @param array $data
     */
    public function __construct(string $stripeSubscriptionId,  array $data)
    {
        $this->subscriptionId = $data['subscriptionId'] ?? null;
        $this->stripeSubscriptionId = $stripeSubscriptionId;
    }

    /**
     * Execute the job.
     *
     * @param StripeService $stripeService
     * @return void
     */
    public function handle(StripeService $stripeService)
    {
        $subscription = null;
        if ($this->subscriptionId) {
            /** @var Subscription $subscription */
            $subscription = Subscription::find($this->subscriptionId)->first();
        }

        try {
            $stripeSubscription = $stripeService->getSubscription($this->stripeSubscriptionId);
            $stripeSubscription->deleteSubscription();
        } catch (\Throwable $e) {
            \Log::error(
                'Subscription Deletion Job processed failed for event ' . Event::CUSTOMER_SUBSCRIPTION_DELETED,
                $e->getTrace()
            );
            return;
        }

        try {
            if ($subscription && $subscription->status !== 'CANCELED') {
                $subscription->status = 'CANCELED';
                $subscription->save();
            }
        } catch (\Throwable $e) {
            \Log::error(
                'Failed to update subscription model after stripe subscription deleted! ',
                $e->getTrace()
            );
        }

        \Log::info(
            'Subscription Deletion Job processed successfully for event ' . Event::CUSTOMER_SUBSCRIPTION_DELETED
        );
    }
}

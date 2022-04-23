<?php

namespace Modules\Laralite\Jobs\Stripe;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Laralite\Services\StripeService;
use Stripe\Event;
use Stripe\Exception\ApiErrorException;

class PaymentSuccess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var array
     */
    private $eventObject;

    /**
     * Create a new job instance.
     *
     * @param array $eventObject
     */
    public function __construct(array $eventObject)
    {
        $this->eventObject = $eventObject;
    }

    /**
     * Execute the job.
     *
     * @param StripeService $stripeService
     * @return void
     * @throws ApiErrorException
     */
    public function handle(StripeService $stripeService)
    {
        $eventObject = $this->eventObject;
        if ($eventObject['billing_reason'] == 'subscription_create') {
            $subscription_id = $eventObject['subscription'];
            $payment_intent_id = $eventObject['payment_intent'];

            # Retrieve the payment intent used to pay the subscription
            $payment_intent = $stripeService->getPaymentIntent($payment_intent_id);
            $stripeService->updateSubscription(
                $subscription_id,
                ['default_payment_method' => $payment_intent->get('payment_method')]
            );
            \Log::info(
                'Payment Success Job process successfully for event ' . Event::INVOICE_PAYMENT_SUCCEEDED
            );
        };
    }
}

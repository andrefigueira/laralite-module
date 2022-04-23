<?php

namespace Modules\Laralite\Http\Controllers;


use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Laralite\Jobs\Stripe\PaymentSuccess;
use Modules\Laralite\Models\Customer;
use Modules\Laralite\Models\Subscription\Price;
use Modules\Laralite\Services\StripeService;
use Modules\Laralite\Traits\ApiResponses;
use Stripe\Event;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;
use Stripe\Webhook;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionPaymentController extends Controller
{
    use ApiResponses;

    /**
     * @var StripeService
     */
    private $stripeService;

    /**
     * PaymentController constructor.
     * @param StripeService $stripeService
     */
    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ApiErrorException
     */
    public function processPayment(Request $request): JsonResponse
    {
        $token = $request->get('token');
        $subscriptionRequest = $request->get('subscription');
        $subscriptionId = $subscriptionRequest['id'] ?? null;;
        $priceId = $subscriptionRequest['priceId'] ?? null;
        $result = $this->stripeService->getPaymentIntent($token);

        if (!$result->get('id')) {
            return response()->json([
                'success' => false,
                'message' => "Error! PLease try again later.",
            ], 400);
        }


        $price = Price::findOrFail($priceId);
        $customerSubscription = Customer\Subscription::findOrFail($subscriptionId);
        /** @var Customer $customer */
        $customer = $customerSubscription->customer()->first();
        $subscription = $customerSubscription->subscription()->first();

        \Log::info('Payment successful for subscription', [
            'token' => $token,
            'customer' => $customer,
            'subscription' => $subscription,
            'total' => $price->price,
        ]);

        if ($creditAmount = $subscription->getAttributeValue('default_initial_credit_amount')) {
            $customer->wallet()->updateOrInsert(
                [
                    'customer_id' => $customer->id,
                    'subscription_plan_id' => $subscription->id
                ],
                [
                    'balance' => \DB::raw('balance + ' . $creditAmount)
                ]
            );
        }
        $customerSubscription->status = 'ACTIVE';
        $stripeSubscription = $this->stripeService->getSubscription($customerSubscription->getStripeSubscriptionId());
        $customerSubscription->expiry_date = $stripeSubscription->getTimeToDate('current_period_end');
        $customerSubscription->save();

        return (new JsonResponse([
            'success' => true,
            'message' => 'Processed payment',
            'data' => [
                'subscription' => $subscription,
                'stripe_result' => $result,
            ],
        ]))->setStatusCode(Response::HTTP_OK);
    }

    protected function createSubscription(Request $request): JsonResponse
    {
        $priceId = $request->get('price_id');
        $customerId = $request->get('customer_id');

        /** @var Price $price */
        $price = Price::findOrFail($priceId);
        /** @var Customer $customer */
        $customer = Customer::findOrFail($customerId);
        $subscription = null;

        $customerSubscription = Customer\Subscription::where([
            'customer_id' => $customer->id,
            'status' => 'ACTIVE',
        ])->first();

        if ($customerSubscription) {
            return $this->error('Customer already has an active subscription', 400);
        }

        /** @var Customer\Subscription $customerSubscription */
        $customerSubscription = $customer
            ->subscriptions()
            ->where('subscription_id', $price->subscription_id)->first();

        try {
            if (!$customer->getStripeCustomerId()) {
                $stripeCustomer = $this->stripeService->saveCustomer([
                    'name' => $customer->name,
                    'email' => $customer->email,
                ]);
                $customer->setStripeCustomerId($stripeCustomer->get('id'));
                $customer->save();
            }

            if ($customerSubscription && $customerSubscription->getStripePaymentIntentId()) {
                $paymentIntent = $this->stripeService->getPaymentIntent($customerSubscription->getStripePaymentIntentId());
                if (!$paymentIntent->requiresPayment()) {
                    $customerSubscription->setStripeSubscriptionId(null);
                    $customerSubscription->setStripePaymentIntentId(null);
                    $customerSubscription->save();
                } else {
                    return $this->success([
                        'subscription_id' => $customerSubscription->id,
                        'client_secret' => $paymentIntent->get('client_secret')
                    ]);
                }
            }

            $subscription = $this->stripeService->createSubscription([
                'customer' => $customer->getStripeCustomerId(),
                'items' => [[
                    'price' => $price->getStripePriceId(),
                ]],
                'payment_behavior' => 'default_incomplete',
                'expand' => ['latest_invoice.payment_intent'],
            ]);
        } catch (\Throwable $e) {
            //TODO handle error
            throw $e;
        }

        if (!$customerSubscription) {
            $customerSubscription = $customer->subscriptions()->create([
                'subscription_id' => $price->subscription()->first()->id,
                'status' => 'INACTIVE'
            ]);
        }

        $customerSubscription->setStripeSubscriptionId($subscription->get('id'));
        $customerSubscription->setStripePaymentIntentId(
            $subscription->getNested('latest_invoice/payment_intent/id')
        );
        $customerSubscription->setStripeSubscriptionEndPeriod($subscription->get('current_period_end'));
        $customerSubscription->expiry_date = $subscription->getTimeToDate('current_period_end');
        $customerSubscription->save();
        $this->stripeService->updateSubscription($subscription->get('id'), [
            'metadata' => [
                'external_id' => $customerSubscription->id
            ]
        ]);

        return $this->success([
            'subscription_id' => $customerSubscription->id,
            'client_secret' => $subscription->getNested('latest_invoice/payment_intent/client_secret')
        ]);
    }

    public function webhook(Request $request): JsonResponse
    {
        // Parse the message body (and check the signature if possible)
        $webhookSecret = config('app.stripe_webhook_secret');
        if ($webhookSecret) {
            try {
                $event = Webhook::constructEvent(
                    $request->getContent(),
                    $request->header('stripe-signature'),
                    $webhookSecret
                )->toArray();
            } catch (\Throwable $e) {
                return $this->error($e->getMessage(), 403);
            }
        } else {
            $event = $request->all();
        }
        $type = $event['type'];
        $object = $event['data']['object'];
        $message = '🔔 ' . $type . ' Webhook received! ';
        switch ($type) {
            case Event::INVOICE_PAID:
                // The status of the invoice will show up as paid. Store the status in your
                // database to reference when a user accesses your service to avoid hitting rate
                // limits.
                \Log::info($message, $object);
                break;
            case Event::INVOICE_PAYMENT_FAILED:
                // If the payment fails or the customer does not have a valid payment method,
                // an invoice.payment_failed event is sent, the subscription becomes past_due.
                // Use this webhook to notify your user that their payment has
                // failed and to retrieve new card details.
                \Log::info($message, $object);
                break;
            case Event::INVOICE_PAYMENT_SUCCEEDED:
                \Log::info($message, $object);
                PaymentSuccess::dispatchAfterResponse($object);
                break;
            case EVENT::CUSTOMER_SUBSCRIPTION_DELETED:
                // handle subscription canceled automatically based
                // upon your subscription settings. Or if the user
                // cancels it.
                \Log::info($message, $object);
                break;
            default:
                // Unhandled event type
        }

        return $this->success($object, 'success');
    }
}
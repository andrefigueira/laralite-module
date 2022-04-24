<?php

namespace Modules\Laralite\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Laralite\Http\Requests\SubscriptionPaymentRequest;
use Modules\Laralite\Jobs\Stripe\PaymentSuccess;
use Modules\Laralite\Jobs\Stripe\SubscriptionDelete;
use Modules\Laralite\Models\Customer;
use Modules\Laralite\Models\Subscription;
use Modules\Laralite\Models\Subscription\Price;
use Modules\Laralite\Services\CustomerSubscriptionService;
use Modules\Laralite\Services\SettingsService;
use Modules\Laralite\Services\StripeService;
use Modules\Laralite\Traits\ApiResponses;
use Stripe\Event;
use Stripe\Exception\ApiErrorException;
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
     * @var SettingsService
     */
    private $settingsService;

    /**
     * @var CustomerSubscriptionService
     */
    private $customerSubscriptionService;

    /**
     * PaymentController constructor.
     * @param StripeService $stripeService
     * @param SettingsService $settingsService
     * @param CustomerSubscriptionService $customerSubscriptionService
     */
    public function __construct(
        StripeService $stripeService,
        SettingsService $settingsService,
        CustomerSubscriptionService  $customerSubscriptionService
    )
    {
        $this->stripeService = $stripeService;
        $this->settingsService = $settingsService;
        $this->customerSubscriptionService = $customerSubscriptionService;
    }

    /**
     * @param SubscriptionPaymentRequest $request
     * @return JsonResponse
     * @throws ApiErrorException
     * @throws \Exception
     */
    public function processPayment(SubscriptionPaymentRequest $request): JsonResponse
    {
        $token = $request->get('token');
        $priceId = $request->get('price_id');
        $paymentIntent = $this->stripeService->getPaymentIntent($token);

        if (!$paymentIntent->get('id') || !$paymentIntent->paymentCompleted()) {
            \Log::error('Payment token is either invalid or payment is incomplete');
            //At this point the payment should have gone throughout successfully so something isn't write here
            return $this->error("Error! PLease try again later.", 500);
        }

        /** @var Customer $customer */
        $customer = auth('customers')->user();
        $subscription = $this->customerSubscriptionService->saveSubscription($customer, (int)$priceId, $paymentIntent);

        \Log::info('Subscription payment successful', [
            'token' => $paymentIntent->get('client_secret'),
            'customer' => $customer,
            'price' => Price::findOrFail($priceId),
        ]);

        return $this->success(
            [
                'subscription' => $subscription,
                'stripe_result' => $paymentIntent,
            ],
            'Processed payment',
            Response::HTTP_OK
        );
    }

    protected function getSubscriptionPaymentIntent(Request $request): JsonResponse
    {
        $priceId = $request->get('price_id');

        /** @var Price $price */
        $price = Price::findOrFail($priceId);
        $totalAmount = $price->price;
        $currency = strtolower($this->settingsService->getCurrency()['value'] ?? '');
        /** @var Customer $customer */
        $customer = auth('customers')->user();

        // Fees
        $feeCollection = $this->settingsService->isFeeCollectionActive();
        // @todo: This is now using the PaymentIntents API
        // Customer details are not being sent to stripe here, we need to do add additional details to the PI creation.
        if ($feeCollection !== false) {
            // Fees are enabled, so send fee amount to connected Stripe Account
            // `feeAmount` is the amount set in the settings
            // `connectedStripeAccount` is the ID of the connected stripe account also set in settings
            $intent = $this->stripeService->createPaymentIntent([
                'amount' => $totalAmount,
                'currency' => $currency,
                'setup_future_usage' => 'off_session',
                'customer' => $customer->getStripeCustomerId(),
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
                'application_fee_amount' => round(($feeCollection['feeAmount'] / 100) * $totalAmount),
                'transfer_data' => [
                    'destination' => $feeCollection['connectedAccountId'],
                ],
                'on_behalf_of' => $feeCollection['connectedAccountId'],
            ]);
        } else {
            $intent = $this->stripeService->createPaymentIntent([
                'amount' => $totalAmount,
                'currency' => $currency,
                'setup_future_usage' => 'off_session',
                'customer' => $customer->getStripeCustomerId(),
                'automatic_payment_methods' => [
                    'enabled' => true,
                ]
            ]);
        }

        return $this->success([
            'price_id' => $price->id,
            'client_secret' => $intent->get('client_secret')
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
                //$this->dispatch((new PaymentSuccess($object))->delay(Carbon::now()->addMinutes(1)));
                PaymentSuccess::dispatchAfterResponse($object);
                break;
            case EVENT::CUSTOMER_SUBSCRIPTION_DELETED:
                // handle subscription canceled automatically based
                // upon your subscription settings. Or if the user
                // cancels it.
                SubscriptionDelete::dispatchAfterResponse(
                    $object['id'],
                    ['subscriptionId' => $object['metadata']['external_id'] ?? null]
                );
                \Log::info($message, $object);
                break;
            default:
                // Unhandled event type
        }

        return $this->success($object, 'success');
    }
}
<?php

namespace Modules\Laralite\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Laralite\Http\Controllers\Api\SessionPaymentTrait;
use Modules\Laralite\Http\Requests\SubscriptionPaymentRequest;
use Modules\Laralite\Jobs\Stripe\PaymentSuccess;
use Modules\Laralite\Jobs\Stripe\SubscriptionDelete;
use Modules\Laralite\Models\Customer;
use Modules\Laralite\Models\Discount;
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
    use SessionPaymentTrait;

    private StripeService $stripeService;
    private SettingsService $settingsService;
    private CustomerSubscriptionService $customerSubscriptionService;

    /**
     * PaymentController constructor.
     * @param StripeService $stripeService
     * @param SettingsService $settingsService
     * @param CustomerSubscriptionService $customerSubscriptionService
     */
    public function __construct(
        StripeService               $stripeService,
        SettingsService             $settingsService,
        CustomerSubscriptionService $customerSubscriptionService
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
        $paymentMethod = $request->get('paymentMethod');
        $paymentIntent = $request->get('paymentIntent');
        $priceId = $request->get('price_id');
        $discountCode = $request->get('discountCode');
        $discount = null;

        if ($paymentIntent) {
            $sessionData = $this->getSessionPaymentData($request);
            $priceId = $sessionData['priceId'];
            $intent = $this->stripeService->getPaymentIntent($paymentIntent);
            try {
                $intent->confirmPayment();
            } catch (\Throwable $e) {
                return response()->json([
                    'success' => false,
                    'message' => "An error occurred during payment confirmation process",
                ], 400);
            }
        } else {
            if ($discountCode) {
                $discount = Discount::firstWhere('code', '=', $discountCode);
            }
            $intent = $this->createPaymentIntent($paymentMethod, $priceId, $discount);
        }

        if (!$intent->get('client_secret')) {
            return response()->json([
                'success' => false,
                'message' => "Error! PLease try again later.",
            ], 400);
        }

        $token = $intent->get('client_secret');

        if ($intent->get('status') === 'requires_action' && $intent->get('next_action/type') === 'use_stripe_sdk') {
            $this->setSessionPaymentData(
                $request,
                [
                    'priceId' => $priceId,
                ]
            );
            return response()->json([
                'intent_secret' => $token,
                'requires_action' => true,
            ], 200);
        }

        if (!$intent->get('id') || !$intent->paymentCompleted()) {
            \Log::error('Payment token is either invalid or payment is incomplete');
            //At this point the payment should have gone throughout successfully so something isn't write here
            return $this->error("Error! PLease try again later.", 500);
        }

        /** @var Customer $customer */
        $customer = auth('customers')->user();
        $subscription = $this->customerSubscriptionService->saveSubscription($customer, (int)$priceId, $intent, $discount);

        \Log::info('Subscription payment successful', [
            'token' => $intent->get('client_secret'),
            'customer' => $customer,
            'price' => Price::findOrFail($priceId),
        ]);

        return $this->success(
            [
                'subscription' => $subscription,
                'stripe_result' => $intent,
            ],
            'Processed payment',
            Response::HTTP_OK
        );
    }

    protected function createPaymentIntent(
        string  $paymentMethodId,
                $priceId,
        ?Discount $discount = null
    ): StripeService\ApiResourceWrapper
    {
        /** @var Price $price */
        $price = Price::findOrFail($priceId);
        $totalAmount = $price->price;
        if ($discount) {
            $totalAmount -= $discount->getDiscount($totalAmount);
        }
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
                'payment_method' => $paymentMethodId,
                'amount' => $totalAmount,
                'currency' => $currency,
                'setup_future_usage' => 'off_session',
                'customer' => $customer->getStripeCustomerId(),
                'application_fee_amount' => round(($feeCollection['feeAmount'] / 100) * $totalAmount),
                'transfer_data' => [
                    'destination' => $feeCollection['connectedAccountId'],
                ],
                'on_behalf_of' => $feeCollection['connectedAccountId'],
                'confirmation_method' => 'manual',
                'confirm' => true,
            ]);
        } else {
            $intent = $this->stripeService->createPaymentIntent([
                'payment_method' => $paymentMethodId,
                'amount' => $totalAmount,
                'currency' => $currency,
                'setup_future_usage' => 'off_session',
                'customer' => $customer->getStripeCustomerId(),
                'confirmation_method' => 'manual',
                'confirm' => true,
            ]);
        }

        return $intent;
    }
}
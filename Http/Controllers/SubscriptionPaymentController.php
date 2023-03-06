<?php

namespace Modules\Laralite\Http\Controllers;


use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Laralite\Http\Controllers\Api\SessionPaymentTrait;
use Modules\Laralite\Http\Requests\SubscriptionPaymentRequest;
use Modules\Laralite\Jobs\Stripe\PaymentSuccess;
use Modules\Laralite\Jobs\Stripe\SubscriptionDelete;
use Modules\Laralite\Models\Customer;
use Modules\Laralite\Models\Discount;
use Modules\Laralite\Models\Payment;
use Modules\Laralite\Models\Subscription;
use Modules\Laralite\Models\Subscription\Price;
use Modules\Laralite\Services\CustomerSubscriptionService;
use Modules\Laralite\Services\Models\Payment\PaymentAmount;
use Modules\Laralite\Services\PaymentService;
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
    private PaymentService $paymentService;

    /**
     * PaymentController constructor.
     * @param StripeService $stripeService
     * @param SettingsService $settingsService
     * @param CustomerSubscriptionService $customerSubscriptionService
     */
    public function __construct(
        StripeService               $stripeService,
        SettingsService             $settingsService,
        CustomerSubscriptionService $customerSubscriptionService,
        PaymentService $paymentService
    )
    {
        $this->paymentService = $paymentService;
        $this->stripeService = $stripeService;
        $this->settingsService = $settingsService;
        $this->customerSubscriptionService = $customerSubscriptionService;
    }

    /**
     * @param SubscriptionPaymentRequest $request
     * @return JsonResponse
     * @throws ApiErrorException
     * @throws Exception
     */
    public function processPayment(SubscriptionPaymentRequest $request): JsonResponse
    {
        $paymentMethod = $request->get('paymentMethod');
        $paymentIntent = $request->get('paymentIntent');
        $priceId = $request->get('price_id');
        $discountCode = $request->get('discountCode');
        $discount = null;
        $isFree = false;
        $intent = null;
        $payment = null;

        if ($paymentIntent) {
            $sessionData = $this->getSessionPaymentData($request);
            $priceId = $sessionData['priceId'];
            try {
                $payment = Payment::where('ext_id', '=', $paymentIntent)->firstOrFail();
                $this->paymentService->confirmPayment($payment);
            } catch (\Throwable $e) {
                \Log::error($e->getMessage(), $e->getTrace());
                return response()->json([
                    'success' => false,
                    'message' => "An error occurred during payment confirmation process",
                ], 400);
            }
        } else {
            if ($discountCode) {
                $discount = Discount::firstWhere('code', '=', $discountCode);
                $isFree = $discount->isOneHundredPercentDiscount();
            }
            if (null === $discount || ($discount && !$isFree)) {
                $price = Price::findOrFail($priceId);
                $amount = $discount ? $price->price - $discount->getDiscount($price->price) : $price->price;
                $payment = $this->paymentService->createPayment(
                    $paymentMethod,
                    new PaymentAmount([
                        'total' => $amount,
                        'subTotal' => $amount,
                        'applyFees' => false,
                    ])
                );
            }
        }

        if (!$isFree) {
            if (!$payment->getStripeClientSecret()) {
                return response()->json([
                    'success' => false,
                    'message' => "Error! PLease try again later.",
                ], 400);
            }

            $token = $payment->getStripeClientSecret();

            if ($payment->status === Payment::STATUS_3DS_AUTHENTICATION_REQUIRED) {
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

            if (!$payment->ext_id || $payment->status !== Payment::STATUS_COMPLETE) {
                \Log::error('Payment token is either invalid or payment is incomplete', $payment->toArray());
                //At this point the payment should have gone throughout successfully so something isn't right here
                return $this->error("Error! PLease try again later.", 500);
            }
        }

        /** @var Customer $customer */
        $customer = auth('customers')->user();
        $subscription = $this->customerSubscriptionService->saveSubscription($customer, (int)$priceId, $payment, $discount);

        \Log::info('Subscription payment successful', [
            'token' => $payment->getStripeClientSecret(),
            'free' => $isFree,
            'paymentMethod' => $paymentMethod,
            'customer' => $customer,
            'price' => Price::findOrFail($priceId),
        ]);

        return $this->success(
            [
                'subscription' => $subscription,
                'stripe_result' => $payment->payment_processor_result,
            ],
            'Processed payment',
            Response::HTTP_OK
        );
    }
}
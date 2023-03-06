<?php

namespace Modules\Laralite\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Log;
use Modules\Laralite\Http\Requests\PaymentRequest;
use Modules\Laralite\Models\Customer;
use Modules\Laralite\Models\Payment;
use Modules\Laralite\Models\Ticket;
use Modules\Laralite\Services\BasketServiceInterface;
use Modules\Laralite\Services\Models\Basket;
use Modules\Laralite\Services\Models\BasketInterface;
use Modules\Laralite\Services\Models\Payment\PaymentAmount;
use Modules\Laralite\Services\OrderService;
use Modules\Laralite\Services\PaymentService;
use Modules\Laralite\Services\SettingsService;
use Modules\Laralite\Services\StripeService;
use Modules\Laralite\Traits\ApiResponses;
use Ramsey\Uuid\Uuid;
use Spatie\Newsletter\NewsletterFacade;
use Stripe\Exception\ApiErrorException;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    use ApiResponses;
    use SessionPaymentTrait;

    private OrderService $orderService;
    private StripeService $stripeService;
    private SettingsService $settingsService;
    private BasketServiceInterface $basketService;
    private PaymentService $paymentService;

    /**
     * PaymentController constructor.
     * @param OrderService $orderService
     * @param StripeService $stripeService
     * @param SettingsService $settingsService
     * @param BasketServiceInterface $basketService
     * @param PaymentService $paymentService
     */
    public function __construct(
        OrderService           $orderService,
        StripeService          $stripeService,
        SettingsService        $settingsService,
        BasketServiceInterface $basketService,
        PaymentService         $paymentService
    )
    {
        $this->settingsService = $settingsService;
        $this->orderService = $orderService;
        $this->stripeService = $stripeService;
        $this->basketService = $basketService;
        $this->paymentService = $paymentService;
    }

    /**
     * @param PaymentRequest $request
     * @return JsonResponse
     * @throws ApiErrorException
     */
    public function processPayment(PaymentRequest $request): JsonResponse
    {
        $paymentMethod = $request->get('paymentMethod');
        $paymentIntent = $request->get('paymentIntent');

        if ($paymentIntent) {
            $sessionData = $this->getSessionPaymentData($request);
            $basket = $this->basketService->getModel($sessionData['basket']);
            $customerData = $sessionData['customer'];
            try {
                $payment = Payment::where('ext_id', '=', $paymentIntent)->firstOrFail();
                $this->paymentService->confirmPayment($payment);
            } catch (\Throwable $e) {
                Log::error($e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => "An error occurred during payment confirmation process",
                ], 400);
            }
        } else {
            $basket = $this->basketService->getModel($request->get('basket', []));
            $customerData = $request->get('customer', []);
            $payment = $this->paymentService->createPayment($paymentMethod, $this->getPaymentAmountObject($basket));
            //$intent = $this->createPaymentIntent($paymentMethod, $basket);
        }

        if (!$payment->getStripeClientSecret()) {
            return response()->json([
                'success' => false,
                'message' => "Error! PLease try again later.",
            ], 400);
        }

        $token = $payment->getStripeClientSecret();
        if ($payment->status_id === Payment::STATUS_3DS_AUTHENTICATION_REQUIRED) {
            $this->setSessionPaymentData(
                $request,
                [
                    'basket' => $basket->toArray(),
                    'customer' => $customerData,
                ]
            );
            return response()->json([
                'intent_secret' => $token,
                'requires_action' => true,
            ], 200);
        }

        Log::info('Processing payment for basket', [
            'token' => $token,
            'basket' => $basket->toArray(),
            'customer' => $customerData,
        ]);

        $sendSms = $customerData['sms'] ?? false;
        $customerEmail = $customerData['email'];
        $currency = $this->settingsService->getCurrency();
        /** @var Customer $customer */
        $customer = Customer::where([
            'email' => $customerEmail,
        ])->first();

        if (!$customer) {
            /** @var Customer $customer */
            $customer = Customer::create([
                'unique_id' => Uuid::uuid4(),
                'name' => $customerData['name'],
                'email' => $customerEmail,
                'password' => !empty($customerData['password']) ? \Hash::make($customerData['password']) : null,
                'newsletter_subscription' => $customerData['newsletter_subscription'] ?? '',
                'numbers' => $customerData['numbers'] ?? '',
            ]);
            $stripeCustomer = $this->stripeService->saveCustomer([
                'name' => $customer->name,
                'email' => $customer->email,
            ]);

            $customer->setStripeCustomerId($stripeCustomer->get('id'));
            $customer->save();
        } else {
            if (!$customer->getStripeCustomerId()) {
                $stripeCustomer = $this->stripeService->saveCustomer([
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'metadata' => [
                        'customer_id' => $customer->unique_id,
                    ],
                ]);
                $customer->setStripeCustomerId($stripeCustomer->get('id'));
            }
            $updateArray['newsletter_subscription->email'] = $customerData['newsletter_subscription']['email'];
            if (empty($customer->password) && !empty($customerData['password'])) {
                $updateArray['password'] = \Hash::make($customerData['password']);
            }
            $customer->update($updateArray);
            $customer->refresh();
        }

        if (!$customer->hasVerifiedEmail()) {
            $customer->sendEmailVerificationNotification();
        }

        $order = $this->orderService->saveOrder([
            'unique_id' => Uuid::uuid4(),
            //TODO Ticket prefix should come from settings
            'confirmation_code' => $this->orderService->generateUniqueCode('TRAP-'),
            'customer_id' => $customer->id,
            'basket' => $basket->toArray(),
            'status' => 1,
            'order_status' => "complete",
            'refunded' => 0,
            'payment_processor_result' => $payment,
        ]);

        $mobile = $customerData['numbers']['mobile'] ?? null;

        try {
            if ($sendSms && $mobile) {
                $mobile = '+1' . $mobile;
                $ticketId = $order->tickets()->first()['unique_id'];
                \Twilio::message(
                    $mobile,
                    'Thank you for your order view your ticket online here: '
                    . url('ticket/view/' . $ticketId)
                );
            }
        } catch (\Throwable $e) {
            Log::error('Failed to send SMS message: ' . $e->getMessage(), $e->getTrace());
        }

        try {
            if ($customerData['newsletter_subscription']['email']) {
                $splitName = explode(' ', $customer->name); // Restricts it to only 2 values, for names like Billy Bob Jones

                $first_name = $splitName[0];
                $last_name = !empty($splitName[1]) ? $splitName[1] : '';
                NewsletterFacade::subscribe($customer['email'], ['FNAME' => $first_name, 'LNAME' => $last_name]);
            }
        } catch (\Throwable $e) {
            Log::error('Failed to subscribe customer to newsletter : ' . $e->getMessage(), $e->getTrace());
        }

        $tickets = Ticket::where('order_id', $order->id)->first();

        if ($order->getAttributeValue('unique_id')) {
            return (new JsonResponse([
                'success' => true,
                'message' => 'Processed payment',
                'data' => [
                    'basket' => $basket->toArray(),
                    'stripe_result' => $payment->payment_processor_result,
                    'order' => $order,
                    'currency' => $currency,
                    'tickets' => $tickets,
                    'subscribed' => $customer['newsletter_subscription']['email'],
                ],
            ]))->setStatusCode(Response::HTTP_OK);
        } else {
            return response()->json([
                'success' => 'false',
                'message' => "Error.",
            ], 400);
        }
    }

    public function getPaymentAmountObject(BasketInterface $basket): PaymentAmount
    {
        return new PaymentAmount([
            'total' => $basket->getTotal(),
            'subtotal' => $basket->getItemsTotal(),
        ]);
    }

    /**
     * @param string $paymentMethodId
     * @param Basket $basket
     * @return StripeService\ApiResourceWrapper
     * @throws ApiErrorException
     */
    private function createPaymentIntent(string $paymentMethodId, BasketInterface $basket): StripeService\ApiResourceWrapper
    {
        $this->basketService->validateBasket($basket);
        $amount = $basket->getTotal();
        $feeCollection = $this->settingsService->isFeeCollectionActive();
        $currencySettings = $this->settingsService->getCurrency() ?: [];
        $feeCollectionAmount = $feeCollection
            ? (int)(round(($feeCollection['feeAmount'] / 100) * $basket->getItemsTotal()))
            + $this->settingsService->getServiceFeeAmount()
            : 0;

        if ($feeCollectionAmount) {
            // Fees are enabled, so send fee amount to connected Stripe Account
            // `feeAmount` is the amount set in the settings
            // `connectedStripeAccount` is the ID of the connected stripe account also set in settings
            $intent = $this->stripeService->createPaymentIntent([
                'payment_method' => $paymentMethodId,
                'amount' => $amount,
                'currency' => strtolower($currencySettings['value']),
                'confirmation_method' => 'manual',
                'application_fee_amount' => $feeCollectionAmount,
                'transfer_data' => [
                    'destination' => $feeCollection['connectedAccountId'],
                ],
                'on_behalf_of' => $feeCollection['connectedAccountId'],
                'confirm' => true,
            ]);
        } else {
            $intent = $this->stripeService->createPaymentIntent([
                'payment_method' => $paymentMethodId,
                'amount' => $amount,
                'confirmation_method' => 'manual',
                'currency' => $currencySettings['value'],
                'confirm' => true,
            ]);
        }

        return $intent;
    }

    public function validateBasket(Request $request): array
    {
        $basket = $this->basketService->getModel($request->get('basket', []));
        $this->basketService->validateBasket($basket);

        return [
            'basket' => $basket->toArray(),
        ];
    }
}

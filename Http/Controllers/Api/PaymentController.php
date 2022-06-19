<?php

namespace Modules\Laralite\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Log;
use Modules\Laralite\Exceptions\AppException;
use Modules\Laralite\Http\Requests\PaymentRequest;
use Modules\Laralite\Models\CreditTransactions;
use Modules\Laralite\Models\Customer;
use Modules\Laralite\Models\Customer\Wallet;
use Modules\Laralite\Models\Order;
use Modules\Laralite\Models\Product;
use Modules\Laralite\Models\Settings;
use Modules\Laralite\Models\Subscription;
use Modules\Laralite\Models\Subscription\Price;
use Modules\Laralite\Models\Ticket;
use Modules\Laralite\Models\TicketScans;
use Modules\Laralite\Services\BasketService;
use Modules\Laralite\Services\OrderService;
use Modules\Laralite\Services\SettingsService;
use Modules\Laralite\Services\StripeService;
use Modules\Laralite\Traits\ApiResponses;
use Ramsey\Uuid\Uuid;
use Spatie\Newsletter\NewsletterFacade;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    use ApiResponses;

    /**
     * @var OrderService
     */
    private $orderService;

    /**
     * @var StripeService
     */
    private $stripeService;

    /**
     * @var SettingsService
     */
    private $settingsService;

    /**
     * @var BasketService
     */
    private $basketService;

    /**
     * PaymentController constructor.
     * @param OrderService $orderService
     * @param StripeService $stripeService
     * @param SettingsService $settingsService
     * @param BasketService $basketService
     */
    public function __construct(
        OrderService $orderService,
        StripeService $stripeService,
        SettingsService $settingsService,
        BasketService $basketService
    )
    {
        $this->settingsService = $settingsService;
        $this->orderService = $orderService;
        $this->stripeService = $stripeService;
        $this->basketService = $basketService;
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
            $basket = $sessionData['basket'];
            $customerData = $sessionData['customer'];
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
            $basket = $request->get('basket');
            $customerData = $request->get('customer');
            $intent = $this->createPaymentIntent($paymentMethod, $basket);
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
                    'basket' => $basket,
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
            'basket' => $basket,
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

        /** @var Order $order */
        $order = $this->orderService->saveOrder([
            'unique_id' => Uuid::uuid4(),
            //TODO Ticket prefix should come from settings
            'confirmation_code' => $this->orderService->generateUniqueCode('TRAP-'),
            'customer_id' => $customer->id,
            'basket' => $basket,
            'status' => 1,
            'order_status' => "complete",
            'refunded' => 0,
            'payment_processor_result' => $intent->toArray(),
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
                    'basket' => $basket,
                    'stripe_result' => $intent->toArray(),
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

    /**
     * @param Request $request
     * @param array $data
     */
    private function setSessionPaymentData(Request $request, array $data)
    {
        $request->session()->put('paymentSession', $data);
    }

    /**
     * @param Request $request
     * @return array
     */
    private function getSessionPaymentData(Request $request): array
    {
        return $request->session()->get('paymentSession', []);
    }

    public function processCreditPayment(PaymentRequest $request): JsonResponse
    {
        $basket = $request->get('basket');
        $customerData = $request->get('customer');
        $sendSms = $customerData['sms'] ?? false;
        $settings = Settings::firstOrFail();

        Log::info('Processing credit payment for basket', [
            'basket' => $basket,
            'customer' => $customerData,
        ]);

        $customerEmail = $customerData['email'];
        $currency = json_decode($settings->settings, true)['currency'];
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

        /** @var Order $order */

        $order = $this->orderService->saveOrder([
            'unique_id' => Uuid::uuid4(),
            'confirmation_code' => $this->orderService->generateUniqueCode('TRAP-'),
            'customer_id' => $customer->id,
            'basket' => $basket,
            'status' => 1,
            'order_status' => "complete",
            'refunded' => 0,
            'payment_processor_result' => null,
        ]);

        $wallet = Wallet::where('customer_id', $customer->id)->first();
        $basketProducts = $basket['products'];
        $price = Arr::pluck($basketProducts, 'price')[0];

        $creditTransaction = CreditTransactions::create(
            [
                'order_id' => $order->id,
                'customer_id' => $customer->id,
                'wallet_id' => $wallet->id,
                'amount' => $price,
            ]
        );

        $walletUpdate = Wallet::where('id', $wallet->id)->first();
        $walletUpdate->balance = ($walletUpdate->balance - $creditTransaction->amount);
        $walletUpdate->save();

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
                    'basket' => $basket,
                    'order' => $order,
                    'credit' => $creditTransaction,
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

    /**
     * @param array $basket
     * @return int
     */
    private function getBasketTotal(array $basket): int
    {
        $basketTotal = 0;

        foreach ($basket['products'] as $product) {
            $fetchedProduct = Product::whereJsonContains('variants', ['sku' => $product['sku']])->firstOrFail();
            $fetchedProductVariants = $fetchedProduct->variants;

            foreach ($fetchedProductVariants as $productVariant) {
                if ($productVariant['sku'] === $product['sku']) {
                    break;
                }
            }

            $productVariantOnSale = $productVariant['pricing']['on_sale'];

            if ($productVariantOnSale) {
                $productVariantPrice = $productVariant['pricing']['sale_price'];
            } else {
                $productVariantPrice = $productVariant['pricing']['price'];
            }

            $formattedProductVariantPrice = preg_replace('/\D/', '', $productVariantPrice);
            $totalLineItemPrice = $formattedProductVariantPrice * $product['quantity'];

            $basketTotal += $totalLineItemPrice;
        }

        return $basketTotal;
    }

    private function isFeeCollectionActive()
    {
        try {
            $settings = Settings::where('id', 1);
            $settings = $settings->first();
            $settings = json_decode($settings->settings);

            if (
                $settings->feeActive === true &&
                !empty($settings->feeAmount) &&
                !empty($settings->stripeSecretKey)
            ) {
                return [
                    'stripeSecretKey' => $settings->stripeSecretKey,
                    'feeAmount' => $settings->feeAmount,
                    'stripeAccessToken' => $settings->stripeAccessToken,
                    'connectedAccountId' => $settings->stripeAccountId,
                ];
            }

        } catch (\Throwable $exception) {
            return false;
        }

        return false;
    }

    protected function createPaymentIntent(string $paymentMethodId, array &$basket): StripeService\ApiResourceWrapper
    {
        $amount = $this->basketService->getBasketTotal($basket);
        $totalAmount = (int)(round($amount, 2) * 100);
        $feeCollection = $this->settingsService->isFeeCollectionActive();
        $currencySettings = $this->settingsService->getCurrency() ?: [];
        $feeCollectionAmount = $feeCollection
            ? (int)(round(($feeCollection['feeAmount'] / 100) * $totalAmount))
            : 0;

        if ($feeCollectionAmount) {
            // Fees are enabled, so send fee amount to connected Stripe Account
            // `feeAmount` is the amount set in the settings
            // `connectedStripeAccount` is the ID of the connected stripe account also set in settings
            $intent = $this->stripeService->createPaymentIntent([
                'payment_method' => $paymentMethodId,
                'amount' => $totalAmount,
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
                'amount' => $totalAmount,
                'confirmation_method' => 'manual',
                'currency' => $currencySettings['value'],
                'confirm' => true,
            ]);
        }

        return $intent;
    }

    public function validateBasket(Request $request): array
    {
        $basket = $request->get('basket', []);

        if (empty($basket)) {
            return $basket;
        }

        $this->basketService->analyzeAndCorrectBasket($basket);

        return [
            'basket' => $basket
        ];
    }
}

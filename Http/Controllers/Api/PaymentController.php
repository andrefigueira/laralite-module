<?php

namespace Modules\Laralite\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Log;
use Modules\Laralite\Http\Requests\PaymentRequest;
use Modules\Laralite\Models\Customer;
use Modules\Laralite\Models\Order;
use Modules\Laralite\Models\Product;
use Modules\Laralite\Models\Settings;
use Modules\Laralite\Services\OrderService;
use Modules\Laralite\Traits\ApiResponses;
use Ramsey\Uuid\Uuid;
use Spatie\Newsletter\NewsletterFacade;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    use ApiResponses;

    private $orderService;

    /**
     * PaymentController constructor.
     * @param OrderService $orderService
     */
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * @param PaymentRequest $request
     * @param $order
     * @return JsonResponse|object
     * @throws ApiErrorException
     */
    public function processPayment(PaymentRequest $request): JsonResponse
    {
        $token = $request->get('token');
        $basket = $request->get('basket');
        $customerData = $request->get('customer');
        $sendSms = $customerData['sms'] ?? false;
        $settings = Settings::firstOrFail();
        $stripeKey = json_decode($settings->settings, true)['stripeSecretKey'];
        $stripe = new StripeClient($stripeKey);

        if (!$stripe) {
            return response()->json([
                'success' => 'false',
                'message' => "Error in Payment Processing. Try again later!"
            ], 400);
        }

        $result = $stripe->paymentIntents->retrieve($token, []);

        if (!$result) {
            return response()->json([
                'success' => false,
                'message' => "Error! PLease try again later."
            ], 400);
        }

        Log::info('Processing payment for basket', [
            'token' => $token,
            'basket' => $basket,
            'customer' => $customerData,
        ]);

        $customerEmail = $customerData['email'];
        $currency = json_decode($settings->settings, true)['currency'];
        /** @var Customer $customer */
        $customer = Customer::where([
            'email' => $customerEmail
        ])->first();

        if (!$customer) {
            $customer = Customer::create([
                'unique_id' => Uuid::uuid4(),
                'name' => $customerData['name'],
                'email' => $customerEmail,
                'password' => !empty($customerData['password']) ? \Hash::make($customerData['password']) : null,
                'newsletter_subscription' => $customerData['newsletter_subscription'] ?? '',
                'numbers' => $customerData['numbers'] ?? '',
            ]);
        } else {
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
            'payment_processor_result' => $result,
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


        if ($order->getAttributeValue('unique_id')) {
            return (new JsonResponse([
                'success' => true,
                'message' => 'Processed payment',
                'data' => [
                    'basket' => $basket,
                    'stripe_result' => $result,
                    'order' => $order,
                    'currency' => $currency,
                    'tickets' => $order->tickets,
                    'subscribed' => $customer['newsletter_subscription']['email'],
                ],
            ]))->setStatusCode(Response::HTTP_OK);
        } else {
            return response()->json([
                'success' => 'false',
                'message' => "Error."
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
                    'connectedAccountId' => $settings->stripeAccountId
                ];
            }

        } catch (\Throwable $exception) {
            return false;
        }

        return false;
    }

    protected function intentSecret(Request $request)
    {
        $amount = $request->get('amount');
        $currency = $request->get('currency');
        $settings = Settings::firstOrFail();

        $stripeKey = json_decode($settings->settings, true)['stripeSecretKey'];

        // @todo: Load stripe key from .env
        // $stripeKey = 'sk_test_51HdwipCYDc7HSRjalZglpakY5as37lC76mOmho2RKGcqYhNf3IcJFi20PcIbPVV9HEXbX9QyZ7BRybYCI5FDI01t00CCj0k2yK';

        $stripe = new StripeClient($stripeKey);

        // Fees
        $feeCollection = $this->isFeeCollectionActive();

        /*$intent = $stripe->paymentIntents->create([
            'amount' => $amount * 100,
            'currency' => $currency,
        ]);*/

        $totalAmount = $amount * 100;

        // @todo: This is now using the PaymentIntents API
        // Customer details are not being sent to stripe here, we need to do add additional details to the PI creation.
        if ($feeCollection !== false) {
            // Fees are enabled, so send fee amount to connected Stripe Account
            // `feeAmount` is the amount set in the settings
            // `connectedStripeAccount` is the ID of the connected stripe account also set in settings
            $intent = $stripe->paymentIntents->create([
                'amount' => $totalAmount,
                'currency' => $currency,
                'application_fee_amount' => round(($feeCollection['feeAmount'] / 100) * $totalAmount),
                'transfer_data' => [
                    'destination' => $feeCollection['connectedAccountId'],
                ],
                'on_behalf_of' => $feeCollection['connectedAccountId']
            ]);
        } else {
            $intent = $stripe->paymentIntents->create([
                'amount' => $totalAmount,
                'currency' => $currency,
            ]);

        }

        return json_encode(array('client_secret' => $intent->client_secret));
    }
}

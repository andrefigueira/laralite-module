<?php

namespace Modules\Laralite\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Barryvdh\LaravelIdeHelper\Helpers;
use Endroid\QrCode\QrCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Log;
use Mail;
use Modules\Laralite\Http\Requests\PaymentRequest;
use Modules\Laralite\Mail\OrderConfirmation;
use Modules\Laralite\Models\Customer;
use Modules\Laralite\Models\Order;
use Modules\Laralite\Models\Product;
use Modules\Laralite\Models\Settings;
use Modules\Laralite\Models\Ticket;
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
        $discount = $request->get('discount');

        $settings = Settings::firstOrFail();

        $stripeKey = json_decode($settings->settings, true)['stripeSecretKey'];

        $stripe = new StripeClient($stripeKey);

        if(!$stripe){
            return response()->json([
                'success' => 'false',
                'message' => "Error in Payment Processing. Try again later!"
            ], 400);
        }

        $result = $stripe->paymentIntents->retrieve($token, []);

        if(!$result){
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
        $basketTotal = $this->getBasketTotal($basket);
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
                'newsletter_subscription' => $customerData['newsletter_subscription'],
            ]);
        } else {
            $updateArray['newsletter_subscription->email'] = $customerData['newsletter_subscription']['email'];
            if (empty($customer->password) && !empty($customerData['password'])) {
                $updateArray['password'] = \Hash::make($customerData['password']);
            }
            $customer->update($updateArray);
            $customer->refresh();
        }

        $confirm_code = generateUniqueCode('TRAP-');

        /*dd($code);*/

        $order = Order::create([
            'unique_id' => Uuid::uuid4(),
            'confirmation_code' => generateUniqueCode('TRAP-'),
            'customer_id' => $customer->id,
            'basket' => $basket,
            'status' => 1,
            'order_status' => "complete",
            'refunded'  => 0,
            'payment_processor_result' => $result,
        ]);

        $orderAssets = $this->generateOrderAssets($order, $basket, $customer);

        // @todo: load from settings
        Mail::to($customerEmail)->send(new OrderConfirmation([
            'order' => $order,
            'customer' => $customer,
            'orderAssets' => $orderAssets,
            'currency' =>  $currency
        ]));

        if($customerData['newsletter_subscription']['email']) {
            $splitName = explode(' ', $customer->name); // Restricts it to only 2 values, for names like Billy Bob Jones

            $first_name = $splitName[0];
            $last_name = !empty($splitName[1]) ? $splitName[1] : '';
            NewsletterFacade::subscribe($customer['email'], ['FNAME'=>$first_name, 'LNAME'=>$last_name]);
        }

        if($orderAssets){
            return (new JsonResponse([
                'success' => true,
                'message' => 'Processed payment',
                'data' => [
                    'basket' => $basket,
                    'stripe_result' => $result,
                    'order' => $order,
                    'currency' => $currency,
                    'tickets' => $order->tickets,
                    'subscribed' =>$customer['newsletter_subscription']['email'],
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

    private function generateTicket($ticketUuid)
    {
        $qrCode = new QrCode($ticketUuid);
        $qrCode->setSize(300);
        $qrCode->setMargin(10);

        return $qrCode;
    }

    private function generateOrderAssets($order, $basket, $customer)
    {
        $generatedTickets = [];

        foreach ($basket['products'] as $index => $product) {
//            if ($product['sku'] === 'TRAPMUSICTICKET') {
                $generatedTickets = $this->getGeneratedTickets($index, $product, $order, $customer);
//            }
        }

        return $generatedTickets;
    }

    private function getGeneratedTickets($index, $product, $order, $customer)
    {
        $quantityGenerated = 0;
        $quantityToGenerate = $product['quantity'];

        // If product variant is `groupable` create just one ticket for the group
        if (isset($product['groupable']) && $product['groupable'] === true) {

            $ticketUuid = Uuid::uuid4();
            $generatedTicket = $this->generateTicket($ticketUuid);

            $generatedTickets[] = Ticket::create([
                'unique_id' => $ticketUuid,
                'customer_id' => $customer->id,
                'order_id' => $order->id,
                'ticket' => [
                    'image' => $generatedTicket->writeDataUri(),
                ],
                'admit_quantity' => $quantityToGenerate,
            ]);
        // else create each individual ticket
        } else {
            while ($quantityGenerated < $quantityToGenerate) {
                $ticketUuid = Uuid::uuid4();
                $generatedTicket = $this->generateTicket($ticketUuid);

                $generatedTickets[] = Ticket::create([
                    'unique_id' => $ticketUuid,
                    'customer_id' => $customer->id,
                    'order_id' => $order->id,
                    'ticket' => [
                        'image' => $generatedTicket->writeDataUri(),
                    ],
                    'admit_quantity' => 1,
                ]);

                $quantityGenerated++;
            }
        }

        return $generatedTickets;
    }

    private function isFeeCollectionActive ()
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
                ];
            }

        } catch (\Throwable $exception) {
           return false;
        }

        return false;
    }

    protected function intentSecret (Request $request)
    {
        $amount = $request->get('amount');
        $currency = $request->get('currency');
        $settings = Settings::firstOrFail();

        $stripeKey = json_decode($settings->settings, true)['stripeSecretKey'];

        // @todo: Load stripe key from .env
        /*$stripeKey = 'sk_test_51HdwipCYDc7HSRjalZglpakY5as37lC76mOmho2RKGcqYhNf3IcJFi20PcIbPVV9HEXbX9QyZ7BRybYCI5FDI01t00CCj0k2yK';*/

        $stripe = new StripeClient($stripeKey);

        // Fees
        $feeCollection = $this->isFeeCollectionActive();

        // @todo: This is now using the PaymentIntents API
        // Customer details are not being sent to stripe here, we need to do add additional details to the PI creation.
        if ($feeCollection !== false) {
            // Fees are enabled, so send fee amount to connected Stripe Account
            // `feeAmount` is the amount set in the settings
            // `connectedStripeAccount` is the ID of the connected stripe account also set in settings
            $intent = $stripe->paymentIntents->create([
                'amount' => $amount * 100,
                'currency' => $currency,
                'application_fee_amount' => $feeCollection['feeAmount'] * 100,
                'transfer_data' => [
                    'destination' => $feeCollection['stripeSecretKey'],
                ],
            ]);
        } else {
            $intent = $stripe->paymentIntents->create([
                'amount' => $amount * 100,
                'currency' => $currency,
            ]);

        }

        return json_encode(array('client_secret' => $intent->client_secret));
    }
}

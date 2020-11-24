<?php

namespace Modules\Laralite\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Endroid\QrCode\QrCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Log;
use Modules\Laralite\Mail\OrderConfirmation;
use Modules\Laralite\Models\Customer;
use Modules\Laralite\Models\Order;
use Modules\Laralite\Models\Product;
use Modules\Laralite\Models\Ticket;
use Ramsey\Uuid\Uuid;
use Stripe\StripeClient;
use Symfony\Component\HttpFoundation\Response;
use Mail;

class PaymentController extends Controller
{
    public function processPayment(Request $request)
    {
        $token = $request->get('token');
        $basket = $request->get('basket');
        $customer = $request->get('customer');
        $discount = $request->get('discount');

        // @todo: Fetch DB stored discount, and confirm value, and apply the discount to the stripe payment request
        // @todo: Add a commission charge to the stripe payment of 5%

        Log::info('Processing payment for basket', [
            'token' => $token['id'],
            'basket' => $basket,
            'customer' => $customer,
        ]);

        $customerEmail = $customer['email'];

        // @todo: Load stripe key from .env
        $stripeKey = 'sk_test_51HdwipCYDc7HSRjalZglpakY5as37lC76mOmho2RKGcqYhNf3IcJFi20PcIbPVV9HEXbX9QyZ7BRybYCI5FDI01t00CCj0k2yK';

        $stripe = new StripeClient($stripeKey);

        $basketTotal = $this->getBasketTotal($basket);

        // @todo: Load from settings
        $paymentDescription = 'TrapMusicMuseum Payment';
        $currency = 'usd';

        $result = $stripe->charges->create([
            'amount' => $basketTotal,
            'currency' => $currency,
            'source' => $token['id'],
            'description' => $paymentDescription,
            'receipt_email' => $customerEmail,
        ]);

        $fetchedCustomer = Customer::where('email', '=', $customerEmail)->get();

        if ($fetchedCustomer->isEmpty()) {
            $fetchedCustomer = Customer::create([
                'unique_id' => Uuid::uuid4(),
                'name' => $customer['name'],
                'email' => $customerEmail,
            ]);
        }

        $fetchedCustomer = $fetchedCustomer->first();

        $order = Order::create([
            'unique_id' => Uuid::uuid4(),
            'customer_id' => $fetchedCustomer->id,
            'basket' => $basket,
            'payment_processor_result' => $result,
            'status' => 1,
        ]);

        $orderAssets = $this->generateOrderAssets($order, $basket, $fetchedCustomer);

        // @todo: load from settings
        Mail::to('andre.figueira@me.com')->send(new OrderConfirmation([
            'order' => $order,
            'customer' => $fetchedCustomer,
            'orderAssets' => $orderAssets,
        ]));

        return (new JsonResponse([
            'success' => true,
            'message' => 'Processed payment',
            'data' => [
                'basket' => $basket,
                'stripe_result' => $result,
                'order' => $order,
            ],
        ]))->setStatusCode(Response::HTTP_OK);
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

    private function generateTicket($order, $index)
    {
        $qrCode = new QrCode($order->unique_id . '_index_' . $index);
        $qrCode->setSize(300);
        $qrCode->setMargin(10);

        return $qrCode;
    }

    private function generateOrderAssets($order, $basket, $customer)
    {
        $generatedTickets = [];

        foreach ($basket['products'] as $index => $product) {
            if ($product['sku'] === 'TRAPMUSICTICKET') {
                $quantityGenerated = 0;
                $quantityToGenerate = $product['quantity'];

                while ($quantityGenerated < $quantityToGenerate) {
                    $generatedTicket = $this->generateTicket($order, $index);

                    $generatedTickets[] = Ticket::create([
                        'unique_id' => Uuid::uuid4(),
                        'customer_id' => $customer->id,
                        'order_id' => $order->id,
                        'ticket' => [
                            'image' => $generatedTicket->writeDataUri(),
                        ],
                    ]);

                    $quantityGenerated++;
                }
            }
        }

        return $generatedTickets;
    }
}

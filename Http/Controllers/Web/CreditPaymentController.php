<?php

namespace Modules\Laralite\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Log;
use Modules\Laralite\Http\Requests\PaymentRequest;
use Modules\Laralite\Models\CreditTransactions;
use Modules\Laralite\Models\Customer;
use Modules\Laralite\Models\Customer\Wallet;
use Modules\Laralite\Models\Order;
use Modules\Laralite\Models\Settings;
use Modules\Laralite\Models\Ticket;
use Modules\Laralite\Repositories\ProductRepository;
use Modules\Laralite\Services\BasketService;
use Modules\Laralite\Services\OrderService;
use Ramsey\Uuid\Uuid;
use Spatie\Newsletter\NewsletterFacade;
use Symfony\Component\HttpFoundation\Response;

class CreditPaymentController extends Controller
{
    private OrderService $orderService;
    private BasketService $basketService;

    public function __construct(OrderService $orderService, BasketService $basketService, ProductRepository $productRep)
    {
        $this->orderService = $orderService;
        $this->basketService = $basketService;
    }

    public function itemClaim(Request $request): void
    {
        $validatedData = $request->validate([
            'productId' => 'required|numeric|min:1',
            'sku' => ['required'],
            'quantity' => 'required|numeric|min:1'
        ]);

        /** @var Customer $user */
        $user = Auth::user();
        $basket = $this->basketService->getModel([
            'products' => [
                [
                    'id' => $request->input('productId'),
                    'sku' => $request->input('sku')
                ]
            ]
        ]);
        $this->basketService->analyzeAndCorrectBasket($basket);
        $total = $basket->getSubtotal();
        $totalCredit = $basket->getTotalCredit();
        $this->orderService->saveOrder();
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
        }

        return response()->json([
            'success' => 'false',
            'message' => "Error.",
        ], 400);
    }
}
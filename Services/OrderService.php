<?php

namespace Modules\Laralite\Services;

use Mail;
use Modules\Laralite\Mail\OrderConfirmation;
use Modules\Laralite\Models\Customer;
use Modules\Laralite\Models\Order;

class OrderService
{
    /**
     * @var SettingsService
     */
    private $settingsService;

    /**
     * @var TicketService
     */
    private $ticketService;

    /**
     * @var StripeService
     */
    private $stripeService;

    public function __construct(
        SettingsService $settingsService,
        TicketService $ticketService,
        StripeService $stripeService
    )
    {
        $this->settingsService = $settingsService;
        $this->ticketService = $ticketService;
        $this->stripeService = $stripeService;
    }

    /**
     * @param $order
     * @param $customer
     * @return array
     */
    private function generateOrderAssets(Order $order, Customer $customer): array
    {
        $generatedTickets = [];
        $basket = json_decode(json_encode($order->basket->products), true);
        foreach ($basket as $index => $product) {
            $generatedTickets[] = $this->ticketService->getGeneratedTickets($index, $product, $order, $customer);
        }

        return $generatedTickets;
    }

    public static function generateUniqueCode($prefix = ''): string
    {
        $code = '';
        $codeExists = false;

        do {
            $code = $prefix . substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
            $order = Order::where('confirmation_code', $code)->first();

            $codeExists = $order && $order->id;

        } while ($codeExists);

        return $code;
    }

    /**
     * @param $orderArray
     * @return Order|null
     */
    public function saveOrder($orderArray): ?Order
    {
        /** @var Order $order */
        $order = Order::create($orderArray);

        try {
            $this->updateOrderPayment($order);
        } catch (\Throwable $e) {
            \Log::error(
                'Failed to update order payment: ' . $e->getMessage(),
                $e->getTrace()
            );
            //TODO create job to have order payment updated
        }

        try {
            $this->sendOrderConfirmationEmail($order);
        } catch (\Throwable $e) {
            \Log::error(
                'Failed sending order confirmation email: ' . $e->getMessage(),
                $e->getTrace()
            );
            //TODO create job to have order confirmation sent
        }


        return $order;
    }

    private function updateOrderPayment(Order $order): void
    {
        $paymentId = $order->payment_processor_result->id ?? null;
        $payload = [
            'metadata' => [
                'order_id' => $order->unique_id,
                'description' => 'Order ID : ' . $order->unique_id
            ],
        ];
        /** @var Customer $customer */
        $customer = $order->customer()->firstOrFail();
        $payload['metadata']['customerId'] = $customer->unique_id;
        $payload['metadata']['customerEmail'] = $customer->email;
        $payload['metadata']['customerName'] = $customer->name;
        $payload['metadata']['taxAmount'] = (float)round($order->basket->taxAmount, 2);
        $payload['metadata']['serviceFee'] = (float)round($order->basket->serviceFee, 2);
        if ($order->basket->discountAmount) {
            $payload['metadata']['discountAmount'] = (float)round($order->basket->discountAmount, 2);
            $payload['metadata']['discountCode'] = !empty($order->basket->discounts)
                ? implode(',', array_column($order->basket->discounts, 'code'))
                : '' ;
        }
        $data = [
            'order' => $order->toArray(),
            'customer' => $customer->toArray(),
        ];

        if ($extCustomerId = $customer->getStripeCustomerId()) {
            $payload['customer'] = $extCustomerId;
        }

        if (null === $paymentId) {
            \Log::alert('cannot update payment with order meta data payment ID is null', $data);
            return;
        }

        \Log::info('Stripe update payment intent payload: ', $payload);
        $this->stripeService->updatePaymentIntent($paymentId, $payload);

        try {

            $this->stripeService->updateConnectedPayment(
                json_decode(json_encode($order->payment_processor_result), true),
                [
                    'metadata' => $payload['metadata'],
                ]
            );
        } catch (\Throwable $e) {
            \Log::error(
                'Failed to update stripe connected payment with meta data: ' . $e->getMessage(),
                $e->getTrace()
            );
            \Log::info('Stripe payload: ', $payload);
            //TODO create job to have connected payment updated
        }
    }

    /**
     * @param Order $order
     * @param string|null $sendToEmail
     */
    public function sendOrderConfirmationEmail(Order $order, string $sendToEmail = null)
    {
        /** @var Customer $customer */
        $customer = Customer::find($order->getAttributeValue('customer_id'));
        $orderAssets = $order->tickets()->get()->isEmpty()
            ? $this->generateOrderAssets($order, $customer)
            : $order->tickets()->get();
        $recipientEmail = $sendToEmail ?: $customer->getAttributeValue('email');

        Mail::to($recipientEmail)->send(new OrderConfirmation([
            'order' => $order,
            'customer' => $customer,
            'orderAssets' => $orderAssets,
            'currency' => $this->settingsService->getCurrency(),
        ]));
    }
}
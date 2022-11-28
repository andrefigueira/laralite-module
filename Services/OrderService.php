<?php

namespace Modules\Laralite\Services;

use Illuminate\Database\Eloquent\Model;
use Mail;
use Modules\Laralite\Mail\OrderConfirmation;
use Modules\Laralite\Models\Customer;
use Modules\Laralite\Models\Order;

class OrderService extends AbstractOrderService
{
    private StripeService $stripeService;

    public function __construct(
        SettingsService $settingsService,
        TicketService $ticketService,
        StripeService $stripeService
    )
    {
        $this->stripeService = $stripeService;
        parent::__construct($settingsService, $ticketService);
    }

    /**
     * @param array $orderData
     * @return Order
     */
    public function saveOrder(array $orderData): Order
    {
        /** @var Order $order */
        $order = Order::create($orderData);
        $this->generateOrderAssets($order);

        if ($order->getPaymentId()) {
            try {
                $this->updateOrderPayment($order);
            } catch (\Throwable $e) {
                \Log::error(
                    'Failed to update order payment: ' . $e->getMessage(),
                    $e->getTrace()
                );
                //TODO create job to have order payment updated
            }
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
        $payload['metadata']['taxAmount'] = $order->basket->taxAmount;
        $payload['metadata']['serviceFee'] = $order->basket->serviceFee;
        if ($order->basket->discountAmount) {
            $payload['metadata']['discountAmount'] = $order->basket->discountAmount;
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
}
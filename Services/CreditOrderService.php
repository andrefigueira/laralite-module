<?php

namespace Modules\Laralite\Services;

use Modules\Laralite\Exceptions\CreditPaymentException;
use Modules\Laralite\Models\Order;
use Throwable;

class CreditOrderService extends AbstractOrderService
{
    private CreditPayment $creditPayment;

    public function __construct(
        CreditPayment $creditPayment,
        SettingsService $settingsService,
        TicketService $ticketService
    )
    {
        $this->creditPayment = $creditPayment;
        parent::__construct($settingsService, $ticketService);
    }

    /**
     * @param array $orderData
     * @return Order
     * @throws CreditPaymentException|Throwable
     */
    public function saveOrder(array $orderData): Order
    {
        /** @var Order $order */
        $order = Order::create($orderData);

        try {
            $this->creditPayment->processTransaction($order);
        } catch (CreditPaymentException $e) {
            $order->order_status = 'failed';
            $order->save();
            throw $e;
        }
        $this->generateOrderAssets($order);

        try {
            $this->sendOrderConfirmationEmail($order);
        } catch (Throwable $e) {
            \Log::error(
                'Failed sending order confirmation email: ' . $e->getMessage(),
                $e->getTrace()
            );
            //TODO create job to have order confirmation sent
        }

        return $order;
    }

    public function update($order, array $updateArray)
    {
        // TODO: Implement update() method.
    }
}
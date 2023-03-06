<?php

namespace Modules\Laralite\Services;

use Modules\Laralite\Mail\OrderConfirmation;
use Modules\Laralite\Models\Customer;
use Modules\Laralite\Models\Order;
use Modules\Laralite\Models\Payment;

abstract class AbstractOrderService implements OrderServiceInterface
{
    private SettingsService $settingsService;
    private TicketService $ticketService;

    public function __construct(
        SettingsService $settingsService,
        TicketService $ticketService
    )
    {
        $this->settingsService = $settingsService;
        $this->ticketService = $ticketService;
    }

    abstract public function saveOrder(array $orderData): Order;

    public function update($order, array $updateArray)
    {
        if (!$order instanceof Order) {
            $order = is_int($order)
                ? Order::findOrFail($order)
                : Order::where('unique_id', '=', $order)->firstOrFail();
        }

        $order->update($updateArray);
    }

    /**
     * TODO Move this to it's own dedicated service E.G. notification service
     * @param Order $order
     * @return array
     */
    public function generateOrderAssets(Order $order): array
    {
        $generatedTickets = [];
        $basket = json_decode(json_encode($order->basket->products), true);
        foreach ($basket as $index => $product) {
            $generatedTickets[] = $this->ticketService->getGeneratedTickets(
                $index,
                $product,
                $order,
                $order->customer()->first()
            );
        }

        return $generatedTickets;
    }

    /**
     * @param string $prefix
     * @return string
     */
    public static function generateUniqueCode(string $prefix = ''): string
    {
        do {
            $code = $prefix . substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
            $order = Order::where('confirmation_code', $code)->first();

            $codeExists = $order && $order->id;

        } while ($codeExists);

        return $code;
    }

    /**
     * TODO Move this to it's own dedicated service E.G. notification service
     * @param Order $order
     * @param string|null $sendToEmail
     */
    public function sendOrderConfirmationEmail(Order $order, string $sendToEmail = null): void
    {
        /** @var Customer $customer */
        $customer = Customer::find($order->getAttributeValue('customer_id'));
        $orderAssets = $order->tickets()->get()->isEmpty()
            ? $this->generateOrderAssets($order)
            : $order->tickets()->get();
        $recipientEmail = $sendToEmail ?: $customer->getAttributeValue('email');

        \Mail::to($recipientEmail)->send(new OrderConfirmation([
            'order' => $order,
            'customer' => $customer,
            'orderAssets' => $orderAssets,
            'currency' => $this->settingsService->getCurrency(),
        ]));
    }
}
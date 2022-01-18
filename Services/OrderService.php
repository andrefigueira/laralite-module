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

    public function __construct(SettingsService $settingsService, TicketService $ticketService)
    {
        $this->settingsService = $settingsService;
        $this->ticketService = $ticketService;
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
        $order = Order::create($orderArray);
        $this->sendOrderConfirmationEmail($order);

        return $order;
    }

    /**
     * @param Order $order
     * @param string|null $sendToEmail
     */
    public function sendOrderConfirmationEmail(Order $order, string $sendToEmail = null)
    {
        /** @var Customer $customer */
        $customer = Customer::find($order->getAttributeValue('customer_id'));
        $orderAssets = $order->tickets()->get() ?: $this->generateOrderAssets($order, $customer);
        $recipientEmail = $sendToEmail ?: $customer->getAttributeValue('email');

        Mail::to($recipientEmail)->send(new OrderConfirmation([
            'order' => $order,
            'customer' => $customer,
            'orderAssets' => $orderAssets,
            'currency' =>  $this->settingsService->getCurrency()
        ]));
    }
}
<?php

use Barryvdh\DomPDF\Facade as PDF;
use Modules\Laralite\Models\Order;
use Modules\Laralite\Models\Settings;
use Modules\Laralite\Models\Ticket;

if (! function_exists('generateTicketPDF')) {
    /**
     * Create a collection from the given value.
     *
     * @param  mixed  $value
     * @return \Illuminate\Support\Collection
     */
    function generateTicketPDF(string $uuid)
    {
        try {
            $ticket = Ticket::where('unique_id', '=', $uuid)->firstOrFail();
        } catch (\Throwable $exception) {
            Log::critical('Failed to generate ticket');

            return abort(404);
        }

        $ticketUuid = $ticket->unique_id;
        $ticketQrCode = $ticket->ticket->image;
        $ticketAdmitQuantity = $ticket->admit_quantity ?? 1;
        $confirmation_code = $ticket->order->confirmation_code;

        $products = $ticket->order->basket->products;
        $ticketPrice = 0;
        $settings = Settings::firstOrFail();
        $currency = json_decode($settings->settings, true)['currency']['currency_symbol'];

        foreach ($products as $product) {
                $ticketPrice = $product->price;
                break;
        }

        // For Testing
        // For some reason SSL was messing with my Image requests....
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed'=> true,
            ]
        ]);

        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => false]);
        $pdf->getDomPDF()->setHttpContext($context);

        $pdf->loadView('trapmusicmuseum::ticket',
            compact(
                'ticketUuid',
                'ticketQrCode',
                'ticketPrice',
                'ticketAdmitQuantity',
                'currency',
                'confirmation_code'
            )
        );

        return $pdf->stream();
    }

    function generateUniqueCode($prefix = '') {
        $code = '';

        $codeExists = false;
        do {
            $code = $prefix . substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
            $order = Order::where('confirmation_code', $code)->first();

            $codeExists = $order && $order->id;

        } while ($codeExists);


        return $code;
    }
}

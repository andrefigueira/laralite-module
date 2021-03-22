<?php

use Barryvdh\DomPDF\Facade as PDF;
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

        $products = $ticket->order->basket->products;
        $ticketPrice = 0;

        foreach ($products as $product) {
            if ($product->sku === 'TRAPMUSICTICKET') {
                $ticketPrice = $product->price;
                break;
            }
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
            )
        );

        return $pdf->stream();
    }
}

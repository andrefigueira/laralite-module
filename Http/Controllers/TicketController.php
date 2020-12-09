<?php

namespace Modules\Laralite\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;
use Modules\Laralite\Models\Ticket;
use Barryvdh\DomPDF\Facade as PDF;

class TicketController extends Controller
{
    /**
     * If UUID is valid, generate a ticket for display
     * @param int $uuid
     * @return Renderable
     */
    public function generateTicket ($uuid)
    {
        try {
            $ticket = Ticket::where('unique_id', '=', $uuid)->firstOrFail();
        } catch (\Throwable $exception) {
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
        $context = stream_context_create(
        [
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed'=> TRUE,
            ]
        ]);

        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
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

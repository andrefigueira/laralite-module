<?php

namespace Modules\Laralite\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Laralite\Models\Ticket;
use Modules\Laralite\Models\Order;
use Symfony\Component\HttpFoundation\Response;
use Barryvdh\DomPDF\Facade as PDF;
use Barryvdh\DomPDF\Options as Options;

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

        $products = $ticket->order->basket->products;
        $ticketPrice = 0;

        foreach ($products as $product) {
            if ($product->sku === 'TRAPMUSICTICKET') {
                $ticketPrice = $product->price;
                break;
            }
        }

        $pdf = PDF::loadView('trapmusicmuseum::ticket', 
            compact(
                'ticketUuid',
                'ticketQrCode',
                'ticketPrice'
            )
        );
    
        return $pdf->stream();
    }
}

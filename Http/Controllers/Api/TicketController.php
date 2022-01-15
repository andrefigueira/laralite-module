<?php

namespace Modules\Laralite\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Mail;
use Modules\Laralite\Mail\OrderCancellation;
use Modules\Laralite\Mail\OrderRefundDetails;
use Modules\Laralite\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Laralite\Models\Order;
use Modules\Laralite\Models\Settings;
use Modules\Laralite\Models\Ticket;
use Modules\Laralite\Traits\ApiResponses;
use Symfony\Component\HttpFoundation\Response;
use Stripe\StripeClient;
use Stripe\Exception\InvalidRequestException;

class TicketController extends Controller
{
    use ApiResponses;

    public function getTicketDetails($uuid)
    {
        return Ticket::with('order.customer')->where('unique_id', '=', $uuid)->first();
    }

    public function redeemTicket($uuid): JsonResponse
    {
        /** @var Ticket $ticket */
        $ticket = Ticket::with('order.customer')->where('unique_id', '=', $uuid)->first();

        $order = $ticket->order;

        if ($ticket && $order->order_status == 'complete' && $order->refunded == 0) {
            $ticket->status = Ticket::STATUS_REDEEMED;
            $ticket->visited_counts = '1';
            try {
                $ticket->updateStatusLog('REDEEMED');
                $ticket->save();
            } catch (\Exception $e) {
                \Log::error($e->getMessage());
                return $this->unknownError();
            }
            return $this->success($ticket->toArray(), "Tickets table updated successfully");
        } else {
            return $this->error("Error: Invalid Ticket", 404);
        }
    }

    public function unredeemTicket($uuid): JsonResponse
    {
        /** @var Ticket $ticket */
        $ticket = Ticket::with('order.customer')->where('unique_id', '=', $uuid)->first();
        $order = $ticket->order;

        if ($ticket && $ticket->status === Ticket::STATUS_REDEEMED) {
            try {
                $ticket->status = Ticket::STATUS_UNREDEEMED;
                $ticket->updateStatusLog(Ticket::STATUS_UNREDEEMED);
                $ticket->save();
            } catch (\Exception $e) {
                \Log::error($e->getMessage());
                return $this->unknownError();
            }
            return $this->success($ticket->toArray(), "Tickets table updated successfully");
        } else {
            return $this->error("Error: Invalid Ticket", 404);
        }
    }

    public function cancelTicket($uuid): JsonResponse
    {
        /** @var Ticket $ticket */
        $ticket = Ticket::with('order.customer')->where('unique_id', '=', $uuid)->first();

        $order = $ticket->order;

        if ($ticket && $ticket->status !== Ticket::STATUS_CANCELLED && $ticket->status !== Ticket::STATUS_REDEEMED) {
            $ticket->status = Ticket::STATUS_CANCELLED;
            try {
                $ticket->updateStatusLog(Ticket::STATUS_CANCELLED);
                $ticket->save();
            } catch (\Exception $e) {
                \Log::error($e->getMessage());
                return $this->unknownError();
            }
            return $this->success($ticket->toArray(), "Tickets table updated successfully");
        } else {
            return $this->error("Error: Invalid Ticket", 404);
        }
    }
}

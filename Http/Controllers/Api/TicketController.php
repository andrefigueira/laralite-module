<?php

namespace Modules\Laralite\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Modules\Laralite\Models\Ticket;
use Modules\Laralite\Services\TicketService;
use Modules\Laralite\Traits\ApiResponses;

class TicketController extends Controller
{
    use ApiResponses;

    /**
     * @var TicketService
     */
    private $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    /**
     * @return JsonResponse
     */
    private function ticketNotFound(): JsonResponse
    {
        return $this->error('Ticket Not Found!', Response::HTTP_NOT_FOUND);
    }

    public function getTicketDetails($uuid)
    {
        try {
            $ticket = $this->ticketService->getTicketByUuid($uuid, ['order.customer']);

            if (!$ticket) {
                return $this->ticketNotFound();
            }
        } catch (\Throwable $e) {
            \Log::error($e->getMessage());
            return $this->unknownError();
        }

        return $ticket;
    }

    /**
     * @param $uuid
     * @return JsonResponse
     */
    public function redeemTicket($uuid): JsonResponse
    {
        if (!$ticket = $this->ticketService->getTicketByUuid($uuid, ['order.customer'])) {
            return $this->ticketNotFound();
        }

        try {
            /** @var Ticket $updatedTicket */
            $this->ticketService->redeemTicket($ticket, ['order.customer']);
        } catch (\Throwable $e) {
            $this->handleCaughtException($e);
        }
        return $this->success($ticket->toArray(), "Tickets table updated successfully");

    }

    /**
     * @param $uuid
     * @return JsonResponse
     */
    public function unredeemTicket($uuid): JsonResponse
    {
        if (!$ticket = $this->ticketService->getTicketByUuid($uuid, ['order.customer'])) {
            return $this->ticketNotFound();
        }

        try {
            /** @var Ticket $updatedTicket */
            $this->ticketService->unredeemTicket($ticket, ['order.customer']);
        } catch (\Exception $e) {
            $this->handleCaughtException($e);
        }

        return $this->success($ticket->toArray(), "Tickets table updated successfully");
    }

    /**
     * @param $uuid
     * @return JsonResponse
     */
    public function cancelTicket($uuid): JsonResponse
    {
        if (!$ticket = $this->ticketService->getTicketByUuid($uuid, ['order.customer'])) {
            return $this->ticketNotFound();
        }

        try {
            /** @var Ticket $updatedTicket */
            $this->ticketService->cancelTicket($ticket, ['order.customer']);
        } catch (\Exception $e) {
            $this->handleCaughtException($e);
        }

        return $this->success($ticket->toArray(), "Tickets table updated successfully");
    }
}

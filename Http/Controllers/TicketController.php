<?php

namespace Modules\Laralite\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Laralite\Services\TicketService;

class TicketController extends Controller
{
    /**
     * @var TicketService
     */
    private TicketService $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    public function view($uuid)
    {
        return $this->ticketService->generateTicketView($uuid, 'HTML');
    }
}

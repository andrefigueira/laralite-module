<?php

namespace Modules\Laralite\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Laralite\Services\TicketService;

class TicketController extends Controller
{
    public function view($uuid)
    {
        return TicketService::generateTicketView($uuid, 'HTML');
    }
}

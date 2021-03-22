<?php

namespace Modules\Laralite\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Modules\Laralite\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Laralite\Models\Order;
use Modules\Laralite\Models\Ticket;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    public function get(Request $request)
    {
        $orders = Order::query();
        $perPage = $request->get('perPage', 1);

        if ($request->get('all') === 'true') {
            return $orders->get();
        }

        if ($request->input('filter') !== 'null') {
            $orders
                ->where('name', 'LIKE', '%' . $request->input('filter') . '%')
                ->orWhere('email', 'LIKE', '%' . $request->input('filter') . '%');
        }

        if ($request->input('sortBy') !== null) {
            $orders->orderBy($request->input('sortBy'), ($request->input('sortDesc') === 'true' ? 'desc' : 'asc'));
        }

        return $orders->paginate($perPage);
    }

    public function getOne($id)
    {
        try {
            return Order::where('id', '=', $id)->firstOrFail();
        } catch (\Throwable $exception) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to get order',
                'errors' => [
                    $exception->getMessage(),
                ],
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function scanTicket($uuid)
    {

        $ticket = Ticket::where('unique_id', '=', $uuid)->first();

        if($ticket) {
                $ticket->validated = '1';
                $ticket->visited_counts = $ticket->visited_counts + 1;
                $ticket->save();
                return response()->json([
                    'success' => 'true',
                    'message' => "Tickets table updated successfully",
                    'ticket'  => $ticket]);
        } else{
            return response()->json([
                'success' => 'false',
                'message' => "Error: Invalid Ticket"
            ], 404);
        }
    }
}

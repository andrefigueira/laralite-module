<?php

namespace Modules\Laralite\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Modules\Laralite\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Laralite\Models\Order;
use Modules\Laralite\Models\Ticket;
use Symfony\Component\HttpFoundation\Response;
use Stripe\StripeClient;
use Stripe\Exception\InvalidRequestException;

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
            if(!$ticket->validated) {
                $ticket->validated = '1';
                $ticket->save();
                return response()->json([
                    'success' => 'true',
                    'message' => "Tickets table updated successfully"
                ]);
            } else {
                return response()->json([
                    'success' => 'false',
                    'message' => "Error: Ticket is already scanned"
                ], 400);
            }
        } else{
            return response()->json([
                'success' => 'false',
                'message' => "Error: Invalid Ticket"
            ], 404);
        }
    }

    public function refund (Request $request)
    {
        $orderId = $request->get('orderId', null);

        if (!$orderId) {
            return response()->json([
                'success' => 'false',
                'message' => "Error: No order ID given"
            ], 400);
        }

        $order = $this->getOne($orderId);
        
        $paymentProcessorResult = $order->payment_processor_result;
        $paymentId = $paymentProcessorResult->id;

        if (!$paymentId) {
            return response()->json([
                'success' => 'false',
                'message' => "Error: Cannot locate paymentId from Stripe"
            ], 400);
        }

        $paymentType = substr($paymentId, 0, 3);

        switch ($paymentType) {
            case 'ch_':
                $type = 'charge';
                break;
            case 'pi_':
                $type = 'payment_intent';
                break;
        }

        try {
            $result = $this->issueRefund($type, $paymentId);
        } catch (\Stripe\Exception\InvalidRequestException $exception) {
            return new JsonResponse([
                'success' => false,
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $exception) {
            return new JsonResponse([
                'success' => false,
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse([
            'success' => true,
            'message' => 'Successfully refunded order',
        ], Response::HTTP_OK);
    }

    private function issueRefund ($type, $paymentId)
    {
        // @todo: Load stripe key from .env
        $stripeKey = 'sk_test_51HdwipCYDc7HSRjalZglpakY5as37lC76mOmho2RKGcqYhNf3IcJFi20PcIbPVV9HEXbX9QyZ7BRybYCI5FDI01t00CCj0k2yK';

        $stripe = new StripeClient($stripeKey);

        $result = $stripe->refunds->create([
            $type => $paymentId,
        ]);

        return $result;
    }
}

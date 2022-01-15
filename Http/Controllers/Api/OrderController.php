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
use Symfony\Component\HttpFoundation\Response;
use Stripe\StripeClient;
use Stripe\Exception\InvalidRequestException;

class OrderController extends Controller
{
    public function get(Request $request)
    {
        $orders = Order::with(['customer']);
        $perPage = $request->get('perPage', 1);

        if ($request->get('all') === 'true') {
            return $orders->get();
        }

        if ($request->input('filter') !== 'null' && $request->input('filter') != '') {
            $orders->whereHas('customer', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->input('filter') . '%')
                    ->orWhere('email', 'LIKE', '%' . $request->input('filter') . '%');
            })->orWhere('confirmation_code', 'LIKE', '%' . $request->input('filter') . '%')->get();
        }

        if ($request->input('sortBy') !== null) {
            $orders->orderBy($request->input('sortBy'), ($request->input('sortDesc') === 'true' ? 'desc' : 'asc'));
        }

        return response()->json($orders->orderBy('created_at', 'DESC')->paginate($perPage));
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

    public function refund (Request $request)
    {
        $orderId = $request->get('orderId', null);

        if (!$orderId) {
            return response()->json([
                'success' => 'false',
                'message' => "Error: No order ID given"
            ], 400);
        }

        $response = $this->refundOrder($orderId);

        if ($response['success']) {
            return new JsonResponse([
                'success' => true,
                'message' => $response['message'],
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'success' => 'false',
                'message' => $response['message']
            ], 400);
        }
    }

    private function refundOrder($orderId)
    {
        $order = $this->getOne($orderId);
        $paymentProcessorResult = $order->payment_processor_result;
        $paymentId = $paymentProcessorResult->id;

        if (!$paymentId) {
            return [
                'success' => 'false',
                'message' => "Error: Cannot locate paymentId from Stripe"
            ];
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
            $settings = Settings::firstOrFail();
            $currency = json_decode($settings->settings, true)['currency'];
            if ($result->status == 'succeeded') {
                $order->refunded = 1;
                $order->save();

                Mail::to($order->customer->email)->send(new OrderRefundDetails([
                    'order' => $order,
                    'customer' => $order->customer,
                    'currency' => $currency
                ]));
            }
        } catch (\Stripe\Exception\InvalidRequestException $exception) {
            return [
                'success' => false,
                'message' => $exception->getError(),
            ];
        } catch (\Exception $exception) {
            return [
                'success' => false,
                'message' => $exception->getMessage(),
            ];
        }

        return [
            'success' => true,
            'message' => 'Successfully refunded order',
            'payment' => $result
        ];
    }

    private function issueRefund($type, $paymentId)
    {
        // @todo: Load stripe key from DB
        $settings = Settings::firstOrFail();

        $stripeKey = json_decode($settings->settings, true)['stripeSecretKey'];

        // @todo: Load stripe key from .env
        /*$stripeKey = 'sk_test_51HdwipCYDc7HSRjalZglpakY5as37lC76mOmho2RKGcqYhNf3IcJFi20PcIbPVV9HEXbX9QyZ7BRybYCI5FDI01t00CCj0k2yK';*/

        $stripe = new StripeClient($stripeKey);

        $result = $stripe->refunds->create([
            $type => $paymentId,
        ]);

        return $result;
    }

    public function cancel(Request $request)
    {
        $orderId = $request->get('orderId', null);

        if (!$orderId) {
            return response()->json([
                'success' => 'false',
                'message' => "Error: No order ID given"
            ], 400);
        }

        $order = Order::where('id', '=', $orderId)->first();

        $order->order_status = 'cancel';
        $order->save();

        $status = $this->refundOrder($orderId);
        $settings = Settings::firstOrFail();
        $currency = json_decode($settings->settings, true)['currency'];

        Mail::to($order->customer->email)->send(new OrderCancellation([
            'order' => $order,
            'customer' => $order->customer,
            'currency' => $currency
        ]));

        return response()->json([
            'success' => 'true',
            'message' => "Order Canceled Successfully."]);

//        if ($order->order_status === 'completed') {
//            $order->order_status = 'canceled';
//            $order->save();
//
//            $ticket->status = 'canceled';
//            $ticket->save();
//
//            return response()->json([
//                'success' => 'true',
//                'message' => "Order Canceled Successfully.",
//                'ticket'  => $order]);
//        } else{
//            return response()->json([
//                'success' => 'false',
//                'message' => "Error: No order ID given"
//            ], 400);
//    }
    }

    public function bulkRefunds(Request $request)
    {
        $orders = $request->get('orders', null);

        foreach ($orders as $order) {

            $orderId = $order['id'];

            if (!$orderId) {
                return response()->json([
                    'success' => 'false',
                    'message' => "Error: No order ID given"
                ], 400);
            }

            $response = $this->refundOrder($orderId);
        }
        if ($response['success']) {
            return new JsonResponse([
                'success' => true,
                'message' => $response['message'],
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'success' => 'false',
                'message' => $response['message']
            ], 400);
        }
    }

    public function reedem(Request $request) {
        $orderId = $request->get('orderId', null);

    if (!$orderId) {
        return response()->json([
            'success' => 'false',
            'message' => "Error: No order ID given"
        ], 400);
    }

    $order = Order::where('id', '=', $orderId)->first();

    $ticket = $order->tickets()->first();

        if ($ticket && $order->order_status == 'complete' && $order->refunded == 0 && ($ticket->visited_counts != 1 || $ticket->visited_counts == null)) {
            $ticket->validated = '1';
            $ticket->visited_counts = '1';
            $ticket->save();
            return response()->json([
                'success' => 'true',
                'message' => "Tickets table updated successfully",
                'ticket' => $ticket]);
        } else {
            return response()->json([
                'success' => 'false',
                'message' => "Error: Invalid Ticket"
            ], 404);
        }
}
}

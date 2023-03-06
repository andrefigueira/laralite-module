<?php

namespace Modules\Laralite\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use JsonException;
use Mail;
use Modules\Laralite\Exceptions\HttpRequestException;
use Modules\Laralite\Exceptions\PaymentException;
use Modules\Laralite\Mail\OrderCancellation;
use Modules\Laralite\Mail\OrderRefundDetails;
use Modules\Laralite\Models\ImportedOrder;
use Modules\Laralite\Models\Order;
use Modules\Laralite\Models\Payment;
use Modules\Laralite\Models\Ticket;
use Modules\Laralite\Services\OrderService;
use Modules\Laralite\Services\PaymentService;
use Modules\Laralite\Services\SettingsService;
use Modules\Laralite\Services\StripeService;
use Modules\Laralite\Services\TicketService;
use Modules\Laralite\Traits\ApiResponses;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    use ApiResponses;

    private TicketService $ticketService;
    private OrderService $orderService;
    private StripeService $stripeService;
    private SettingsService $settingsService;
    private PaymentService $paymentService;

    public function __construct(
        OrderService    $orderService,
        TicketService   $ticketService,
        StripeService   $stripeService,
        SettingsService $settingsService,
        PaymentService  $paymentService
    )
    {
        $this->orderService = $orderService;
        $this->ticketService = $ticketService;
        $this->stripeService = $stripeService;
        $this->settingsService = $settingsService;
        $this->paymentService = $paymentService;
    }

    public function get(Request $request)
    {
        $orders = Order::with(['customer', 'tickets']);
        $perPage = $request->get('perPage', 1);


        if ($request->get('all') === 'true') {
            return $orders->get();
        }

        if ($request->input('orderstatus') !== 'null' && $request->input('orderstatus') != '') {
            if ($request->input('orderstatus') === 'Cancel') {
                $orders->where('order_status', '=', 'cancel');
            }
            if ($request->input('orderstatus') === 'Complete') {
                $orders->where('order_status', '=', 'complete');
            }
            if ($request->input('orderstatus') === 'Refunded') {
                $orders->where('refunded', '=', 1);
            }
            if ($request->input('orderstatus') === 'Reedemed') {
                $orders->whereHas('tickets', function ($q) use ($request) {
                    $q->where('status', '=', 'REEDEMED');
                });
            }
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

        DB::enableQueryLog();
        $result = $orders->orderBy('created_at', 'DESC')->paginate($perPage);
        \Illuminate\Support\Facades\Log::info(DB::getQueryLog());
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

    /**
     * @throws HttpRequestException
     * @throws PaymentException
     * @throws JsonException
     */
    public function refund(Request $request)
    {
        $orderId = $request->get('orderId', null);

        if (!$orderId) {
            return response()->json([
                'success' => 'false',
                'message' => "Error: No order ID given",
            ], 400);
        }

        $data = [
            'reason' => $request->post('reason'),
        ];

        $response = $this->refundOrder($orderId, $data);

        if ($response['success']) {
            return new JsonResponse([
                'success' => true,
                'message' => $response['message'],
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'success' => 'false',
                'message' => $response['message'],
            ], 400);
        }
    }

    /**
     * @throws HttpRequestException
     * @throws PaymentException|JsonException
     */
    private function refundOrder($orderId, array $data = []): array
    {
        $order = $this->getOne($orderId);
        try {
            /** @var Payment $payment */
            $payment = $order->payments()->firstOrFail();
        } catch(ModelNotFoundException $e) {
            //Order is possibly and older order with no payments attached so let create it now
            $payment = $this->paymentService->createPaymentFromResults($order->payment_processor_result);
            $payment->setPayableType(Order::class);
            $payment->setPayableId($order->id);
            $payment->save();
        }

        $this->paymentService->refundPayment($payment, $data);
        $currency = $this->settingsService->getCurrency();
        if (Payment::STATUS_REFUNDED === $payment->status) {
            $order->refunded = 1;
            $order->save();

            Mail::to($order->customer->email)->send(new OrderRefundDetails([
                'order' => $order,
                'customer' => $order->customer,
                'currency' => $currency,
            ]));
        }

        return [
            'success' => true,
            'message' => 'Successfully refunded order',
            'payment' => $payment->toArray(),
        ];
    }

    public function cancel(Request $request): JsonResponse
    {
        $orderId = $request->get('orderId', null);

        if (!$orderId) {
            return response()->json([
                'success' => 'false',
                'message' => "Error: No order ID given",
            ], 400);
        }

        $errors = [];
        /** @var Order $order */
        $order = Order::where('id', '=', $orderId)->first();
        $order->order_status = 'cancel';
        $order->save();

        $this->refundOrder($orderId);
        $currency = $this->settingsService->getCurrency();

        /** @var Ticket $ticket */
        $ticket = $order->tickets()->first();

        if ($ticket) {
            try {
                $this->ticketService->cancelTicket($ticket);
            } catch (\Exception $e) {
                $this->handleCaughtException($e);
            }
        }

        Mail::to($order->customer->email)->send(new OrderCancellation([
            'order' => $order,
            'customer' => $order->customer,
            'currency' => $currency,
        ]));

        return $this->success(
            $order->toArray(),
            'Order Canceled Successfully.',
            $errors ? Response::HTTP_PARTIAL_CONTENT : Response::HTTP_OK,
            $errors
        );
    }

    public function bulkRefunds(Request $request)
    {
        $orders = $request->get('orders', null);

        foreach ($orders as $order) {

            $orderId = $order['id'];

            if (!$orderId) {
                return response()->json([
                    'success' => 'false',
                    'message' => "Error: No order ID given",
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
                'message' => $response['message'],
            ], 400);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function redeem(Request $request): JsonResponse
    {
        $orderId = $request->get('orderId', null);

        if (!$orderId) {
            return response()->json([
                'success' => 'false',
                'message' => "Error: No order ID given",
            ], 400);
        }

        $order = Order::where('id', '=', $orderId)->first();
        /** @var Ticket $ticket */
        $ticket = $order->tickets()->first();
        $importOrder = ImportedOrder::where('order_id', '=', $order->getAttribute('unique_id'));

        try {
            if (!$ticket && $importOrder) {
                $this->orderService->generateOrderAssets($order);
                $order->refresh();
                $ticket = $order->tickets()->first();
            }
            $ticket = $this->ticketService->redeemTicket($ticket->unique_id);
            $this->orderService->update($order, ['order_status' => 'complete']);
        } catch (\Exception $e) {
            $this->handleCaughtException($e);
        }

        return $this->success($ticket ? $ticket->toArray() : [], 'Tickets redeemed successfully');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function unredeem(Request $request): JsonResponse
    {
        $orderId = $request->get('orderId', null);

        if (!$orderId) {
            return response()->json([
                'success' => 'false',
                'message' => "Error: No order ID given",
            ], 400);
        }

        $order = Order::where('id', '=', $orderId)->first();
        /** @var Ticket $ticket */
        $ticket = $order->tickets()->first();

        try {
            $ticket = $this->ticketService->unredeemTicket($ticket->unique_id);
            $this->orderService->update($order, ['order_status' => 'pending']);
        } catch (\Exception $e) {
            $this->handleCaughtException($e);
        }

        return $this->success($ticket ? $ticket->toArray() : [], 'Ticket unredeemed successfully');
    }

    public function sendOrderConfirmationEmail(Request $request)
    {
        $orderId = $request->get('orderId', null);
        $email = $request->get('email', null);

        if (!$orderId) {
            return $this->error('Error: No order ID given', 400);
        }

        $order = Order::where('id', '=', $orderId)->first();

        try {
            $this->orderService->sendOrderConfirmationEmail($order, $email);
        } catch (\Exception $e) {
            $this->handleCaughtException($e);
        }

        return $this->success([], 'Order confirmation email sent successfully');
    }
}

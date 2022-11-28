<?php

namespace Modules\Laralite\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Log;
use Modules\Laralite\Exceptions\CreditPaymentException;
use Modules\Laralite\Http\Requests\PaymentRequest;
use Modules\Laralite\Models\CreditTransactions;
use Modules\Laralite\Models\Customer;
use Modules\Laralite\Models\Customer\Wallet;
use Modules\Laralite\Models\Order;
use Modules\Laralite\Models\Settings;
use Modules\Laralite\Models\Ticket;
use Modules\Laralite\Repositories\ProductRepository;
use Modules\Laralite\Services\BasketService\Credit;
use Modules\Laralite\Services\BasketService\Standard;
use Modules\Laralite\Services\BasketServiceInterface;
use Modules\Laralite\Services\CreditPayment;
use Modules\Laralite\Services\OrderService;
use Modules\Laralite\Services\OrderServiceInterface;
use Modules\Laralite\Traits\ApiResponses;
use Ramsey\Uuid\Uuid;
use Spatie\Newsletter\NewsletterFacade;
use Symfony\Component\HttpFoundation\Response;

class CreditPaymentController extends Controller
{
    use ApiResponses;
    private OrderServiceInterface $orderService;
    private BasketServiceInterface $basketService;

    public function __construct(
        OrderServiceInterface  $orderService,
        BasketServiceInterface $basketService
    )
    {
        $this->orderService = $orderService;
        $this->basketService = $basketService;
    }

    public function itemClaim(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'productId' => 'required|numeric|min:1',
            'sku' => ['required'],
            'quantity' => 'required|numeric|min:1'
        ]);

        /** @var Customer $user */
        $customer = Auth::user();
        $basket = $this->basketService->getModel([
            'products' => [
                [
                    'id' => $request->input('productId'),
                    'sku' => $request->input('sku'),
                    'quantity' => $request->input('quantity'),
                ]
            ]
        ]);
        $this->basketService->analyzeAndCorrectBasket($basket);
        $order = $this->orderService->saveOrder([
            'unique_id' => Uuid::uuid4(),
            'confirmation_code' => $this->orderService->generateUniqueCode('TRAP-'),
            'customer_id' => $customer->id,
            'basket' => $basket->toArray(),
            'status' => 1,
            'order_status' => "complete",
            'refunded' => 0,
        ]);
        return $this->success($order->toArray(), 'Item claim successful!');
    }
}
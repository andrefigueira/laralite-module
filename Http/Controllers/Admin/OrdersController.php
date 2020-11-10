<?php

namespace Modules\Laralite\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\Laralite\Models\Order;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrdersController extends Controller
{
    public function index()
    {
        return view('laralite::admin.orders');
    }

    public function view(string $uniqueId)
    {
        $order = Order::where('unique_id', '=', $uniqueId)->get();

        if ($order === null) {
            throw new NotFoundHttpException('Order not found');
        }

        return view('laralite::admin.orders.view', [
            'order' => $order,
        ]);
    }
}

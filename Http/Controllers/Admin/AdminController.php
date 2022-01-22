<?php

namespace Modules\Laralite\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Laralite\Models\Order;

class AdminController extends Controller
{
    public function home()
    {
        return view('laralite::admin.home');
    }

    public function getSales(Request $request) {
        $startDate = $request->get('startDate') ?? Carbon::now()->format('Y-m-d');
        $endDate = $request->get('endDate') ?? Carbon::now()->format('Y-m-d');

        $orders = Order::all('created_at')
            ->where('created_at','>=',$startDate)
            ->where('created_at','<=',$endDate)
            ->groupBy(
                function ($val) {
                    return Carbon::parse($val->created_at)->format('d-M-Y');
                });

        return response()->json($orders);

    }
}

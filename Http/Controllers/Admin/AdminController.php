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
        $startDate = $request->get('startDate');
        $endDate = $request->get('endDate') ?? Carbon::now()->format('Y-d-m');

        if(empty($startDate) || empty($endDate)) {
            $orders = Order::all('created_at')
                ->where('created_at', '>=', Carbon::now()->subDay())
                ->groupBy(
                    function ($val) {
                        return Carbon::parse($val->created_at)->format('h:i a');
                    });
        } else {
            $orders = Order::all('created_at')
                ->where('created_at','>=',$startDate)
                ->where('created_at','<=',$endDate)
                ->groupBy(
                    function ($val) {
                        return Carbon::parse($val->created_at)->format('d-m-y');
                    });
        }

        return response()->json($orders);

    }
}

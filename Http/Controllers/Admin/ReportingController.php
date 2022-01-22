<?php

namespace Modules\Laralite\Http\Controllers\Admin;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Laralite\Models\Order;

class ReportingController
{
    public function index()
    {
        return view('laralite::admin.reporting');
    }

}

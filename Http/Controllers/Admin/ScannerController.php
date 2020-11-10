<?php

namespace Modules\Laralite\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Laralite\Models\Customer;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ScannerController extends Controller
{
    public function scanner()
    {
        return view('laralite::admin.scanner');
    }
}

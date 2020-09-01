<?php

namespace Modules\Laralite\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class ComponentController extends Controller
{
    public function index()
    {
        return view('laralite::admin.component');
    }
}

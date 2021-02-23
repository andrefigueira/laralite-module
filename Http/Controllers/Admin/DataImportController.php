<?php

namespace Modules\Laralite\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DataImportController extends Controller
{
    public function index()
    {
        return view('laralite::admin.import');
    }
}

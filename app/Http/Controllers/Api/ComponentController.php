<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Component;

class ComponentController extends Controller
{
    public function get()
    {
        return Component::all();
    }
}

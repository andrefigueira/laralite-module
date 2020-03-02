<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;

class PagesController extends Controller
{
    public function index()
    {
        $pages = Page::all();

        return view('admin.pages', [
            'pages' => $pages,
        ]);
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function edit()
    {
        return view('admin.pages.create');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;

class PagesController extends Controller
{
    public function index()
    {
        return view('admin.pages');
    }

    public function create()
    {
        return view('admin.pages.form', [
            'type' => 'create',
        ]);
    }

    public function edit($id)
    {
        $page = Page::where('id', '=', $id)->firstOrFail();

        return view('admin.pages.form', [
            'type' => 'edit',
            'page' => $page,
        ]);
    }
}

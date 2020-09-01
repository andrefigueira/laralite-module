<?php

namespace Modules\Laralite\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Laralite\Models\Page;

class PagesController extends Controller
{
    public function index()
    {
        return view('laralite::admin.pages');
    }

    public function create()
    {
        return view('laralite::admin.pages.form', [
            'type' => 'create',
        ]);
    }

    public function edit($id)
    {
        $page = Page::where('id', '=', $id)->firstOrFail();

        return view('laralite::admin.pages.form', [
            'type' => 'edit',
            'page' => $page,
        ]);
    }
}

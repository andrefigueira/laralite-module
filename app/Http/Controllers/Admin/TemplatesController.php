<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Template;
use Illuminate\Http\Request;

class TemplatesController extends Controller
{
    public function index()
    {
        return view('admin.templates');
    }

    public function create()
    {
        return view('admin.templates.form', [
            'type' => 'create',
        ]);
    }

    public function edit($id)
    {
        $template = Template::where('id', '=', $id)->firstOrFail();

        return view('admin.templates.form', [
            'type' => 'edit',
            'template' => $template,
        ]);
    }
}

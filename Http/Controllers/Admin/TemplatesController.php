<?php

namespace Modules\Laralite\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\Laralite\Models\Template;
use Illuminate\Http\Request;

class TemplatesController extends Controller
{
    public function index()
    {
        return view('laralite::admin.templates');
    }

    public function create()
    {
        return view('laralite::admin.templates.form', [
            'type' => 'create',
        ]);
    }

    public function edit($id)
    {
        $template = Template::where('id', '=', $id)->firstOrFail();

        return view('laralite::admin.templates.form', [
            'type' => 'edit',
            'template' => $template,
        ]);
    }
}

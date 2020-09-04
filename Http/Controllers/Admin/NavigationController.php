<?php

namespace Modules\Laralite\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\Laralite\Models\Navigation;
use Illuminate\Http\Request;

class NavigationController extends Controller
{
    public function index()
    {
        return view('laralite::admin.navigation');
    }

    public function create()
    {
        return view('laralite::admin.navigation.form', [
            'type' => 'create',
        ]);
    }

    public function edit($id)
    {
        $navigation = Navigation::where('id', '=', $id)->firstOrFail();

        return view('laralite::admin.navigation.form', [
            'type' => 'edit',
            'navigation' => $navigation,
        ]);
    }
}

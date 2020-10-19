<?php

namespace Modules\Laralite\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\Laralite\Models\Roles;

class RolesController extends Controller
{
    public function index()
    {
        return view('laralite::admin.roles');
    }

    public function create()
    {
        $roles = Roles::pluck('name','name')->all();

        return view('laralite::admin.roles.form', [
            'type' => 'create',
        ]);
    }

    public function edit($id)
    {
        $role = Roles::where('id', '=', $id)->firstOrFail();

        return view('laralite::admin.roles.form', [
            'type' => 'edit',
            'role' => $role,
        ]);
    }
}

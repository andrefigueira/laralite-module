<?php

namespace Modules\Laralite\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\Laralite\Models\Permissions;

class PermissionsController extends Controller
{
    public function index()
    {
        return view('laralite::admin.permissions');
    }

    public function create()
    {
        $roles = Permissions::pluck('name','name')->all();

        return view('laralite::admin.permissions.form', [
            'type' => 'create',
        ]);
    }

    public function edit($id)
    {
        $permission = Permissions::where('id', '=', $id)->firstOrFail();

        return view('laralite::admin.permissions.form', [
            'type' => 'edit',
            'role' => $permission,
        ]);
    }
}

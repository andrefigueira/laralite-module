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
        return view('laralite::admin.permissions.form', [
            'type' => 'create',
        ]);
    }

    public function edit($id)
    {
        $permission = Permissions::where('id', '=', $id)->firstOrFail();
        
        return view('laralite::admin.permissions.form', [
            'type' => 'edit',
            'permission' => $permission,
        ]);
    }
}

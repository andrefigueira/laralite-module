<?php

namespace Modules\Laralite\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\Laralite\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index()
    {
        return view('laralite::admin.users');
    }

    public function create()
    {
        return view('laralite::admin.users.form', [
            'type' => 'create',
        ]);
    }

    public function edit($id)
    {
        $user = User::where('id', '=', $id)->firstOrFail();

        return view('laralite::admin.users.form', [
            'type' => 'edit',
            'user' => $user,
        ]);
    }
}

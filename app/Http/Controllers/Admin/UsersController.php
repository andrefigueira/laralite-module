<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index()
    {
        return view('admin.users');
    }

    public function create()
    {
        return view('admin.users.form', [
            'type' => 'create',
        ]);
    }

    public function edit($id)
    {
        $user = User::where('id', '=', $id)->firstOrFail();

        return view('admin.users.form', [
            'type' => 'edit',
            'user' => $user,
        ]);
    }
}

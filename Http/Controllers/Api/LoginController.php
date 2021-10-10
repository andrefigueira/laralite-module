<?php

namespace Modules\Laralite\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;


class LoginController extends Controller
{
    use ApiResponses;

    private function validateLogin($request)
    {
        return $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string',
        ]);
    }

    public function login(Request $request)
    {
        $parameters = $this->validateLogin($request);

        if (!Auth::guard('customers')->attempt($parameters)) {
            $this->error('Login failed', Response::HTTP_BAD_REQUEST);
        }

        $customer = Auth::guard('customers')->user();

        if ($customer === null) {
            return $this->error('Failed to login', Response::HTTP_BAD_REQUEST);
        }

        return $customer->createToken('My Token');
    }
}

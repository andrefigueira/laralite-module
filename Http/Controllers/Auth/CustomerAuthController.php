<?php

namespace Modules\Laralite\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Laralite\Http\Requests\LoginRequest;
use Modules\Laralite\Traits\ApiResponses;


class CustomerAuthController extends Controller
{
    use ApiResponses;

    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();
        if (!auth('customers')->attempt($data)) {
            return $this->error('Invalid email and password Please try again', 401);
        }

        return $this->success([
            'user' => auth('customers')->user()
        ], 'Login Successful', '200');
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        auth('customers')->logout();

        return redirect('/');
    }

}

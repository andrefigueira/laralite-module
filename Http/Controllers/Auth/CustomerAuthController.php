<?php

namespace Modules\Laralite\Http\Controllers\Auth;

use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Modules\Laralite\Http\Requests\LoginRequest;
use Modules\Laralite\Traits\ApiResponses;
use Password;
use Symfony\Component\HttpFoundation\Response;


class CustomerAuthController extends ForgotPasswordController
{
    use ApiResponses;
    use ResetsPasswords;

    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();
        if (!auth('customers')->attempt($data)) {
            return $this->error('Invalid email and password Please try again', 401);
        }

        return $this->success([
            'user' => auth('customers')->user()
        ], 'Login Successful', Response::HTTP_ACCEPTED);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        auth('customers')->logout();

        return redirect('/');
    }

    public function broker(): PasswordBroker
    {
        return Password::broker('customers');
    }
}

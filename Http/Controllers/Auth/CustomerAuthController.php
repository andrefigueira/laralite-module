<?php

namespace Modules\Laralite\Http\Controllers\Auth;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Laralite\Http\Requests\LoginRequest;
use Modules\Laralite\Models\Customer;
use Modules\Laralite\Traits\ApiResponses;
use Password;
use Symfony\Component\HttpFoundation\Response;


class CustomerAuthController extends ForgotPasswordController
{
    use ApiResponses;
    use ResetsPasswords;
    use VerifiesEmails;
    use RedirectsUsers;

    protected $redirectTo = '/';

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
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function verify(Request $request)
    {
        /** @var Customer $customer */
        $customer = Customer::find($request->get('id'));

        if (!hash_equals((string) $request->get('hash'), sha1($customer->getEmailForVerification()))) {
            throw new AuthorizationException;
        }

        if ($customer->hasVerifiedEmail()) {
            return $request->wantsJson()
                ? new JsonResponse([], 204)
                : redirect($this->redirectPath());
        }

        if ($customer->markEmailAsVerified()) {
            event(new Verified($customer));
        }

        if ($response = $this->verified($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect($this->redirectPath() . '?email-verified=1');
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

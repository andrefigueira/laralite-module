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
use Illuminate\Validation\ValidationException;
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
            'user' => auth('customers')->user(),
        ], 'Login Successful', Response::HTTP_ACCEPTED);
    }

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function verify(Request $request)
    {
        /** @var Customer $customer */
        $customer = Customer::find($request->get('id'));

        if (!hash_equals((string)$request->get('hash'), sha1($customer->getEmailForVerification()))) {
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

    /**
     * Send a reset link to the given user.
     *
     * @param Request $request
     * @return RedirectResponse|JsonResponse
     * @throws ValidationException
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = $this->broker()->sendResetLink(
            $this->credentials($request)
        );

        return new JsonResponse([
            'message' => 'If we found an account associated with that email address,
             we\'ve sent password reset instructions.',
        ]);
    }
}

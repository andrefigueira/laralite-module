<?php

namespace Modules\Laralite\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Laralite\Http\Requests\AccountUpdateRequest;
use Modules\Laralite\Http\Requests\LoginRequest;
use Modules\Laralite\Http\Requests\PasswordChangeRequest;
use Modules\Laralite\Http\Requests\SignUpRequest;
use Modules\Laralite\Models\Customer;
use Modules\Laralite\Traits\ApiResponses;
use Ramsey\Uuid\Uuid;
use Auth;


class CustomerAuthController extends Controller
{
    use ApiResponses;

    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();
        if (!auth('customers')->attempt($data)) {
            return $this->error('Invalid email and password Please try again', 401);
        }

        return $this->success([], 'Login Successful', '200');
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        auth('customers')->logout();

        return redirect('/');
    }

}

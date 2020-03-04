<?php

namespace App\Http\Controllers\Api;

use Log;
use Hash;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function get(Request $request)
    {
        return User::paginate();
    }

    public function getOne($id)
    {
        try {
            return User::where('id', '=', $id)->firstOrFail();
        } catch (\Throwable $exception) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to get user',
                'errors' => [
                    $exception->getMessage(),
                ],
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);

        try {
            $user = User::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
            ]);

            Log::info('Created user', [
                'request' => $request->all(),
                'user' => $user,
            ]);

            return new JsonResponse([
                'success' => true,
                'message' => 'Created new user',
                'data' => [
                    'user' => $user,
                ],
            ], Response::HTTP_CREATED);
        } catch (\Throwable $exception) {
            Log::error('Failed to create user', [
                'message' => $exception->getMessage(),
            ]);

            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to create user',
                'errors' => [
                    $exception->getMessage(),
                ],
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
        ]);

        try {
            $user = User::where('id', '=', $id)->firstOrFail();

            $user->update([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
            ]);

            if ($request->get('password') !== '') {
                $user->update([
                    'password' => Hash::make($request->get('password')),
                ]);
            }

            Log::info('Updated user', [
                'request' => $request->all(),
                'user' => $user,
            ]);

            return $user;
        } catch (\Throwable $exception) {
            Log::error('Failed to update user', [
                'message' => $exception->getMessage(),
            ]);

            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to update user',
                'errors' => [
                    $exception->getMessage(),
                ],
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete($id)
    {
        try {
            $user = User::where('id', '=', $id)->firstOrFail();

            $user->delete();

            return new JsonResponse([], Response::HTTP_NO_CONTENT);
        } catch (\Throwable $exception) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to delete user',
                'errors' => [
                    $exception->getMessage(),
                ],
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}

<?php

namespace Modules\Laralite\Http\Controllers\Api;

use Log;
use Hash;
use App\Http\Controllers\Controller;
use Modules\Laralite\Models\Roles;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RolesController extends Controller
{
    public function get(Request $request)
    {
        $roles = Roles::query();
        $perPage = $request->get('perPage', 1);

        if ($request->get('all') === 'true') {
            return $roles->get();
        }

        if ($request->input('filter') !== 'null') {
            $roles
                ->where('name', 'LIKE', '%' . $request->input('filter') . '%')
                ->orWhere('guard_name', 'LIKE', '%' . $request->input('filter') . '%');
        }

        if ($request->input('sortBy') !== null) {
            $roles->orderBy($request->input('sortBy'), ($request->input('sortDesc') === 'true' ? 'desc' : 'asc'));
        }

        return $roles->paginate($perPage);
    }

    public function getOne($id)
    {
        try {
            return Roles::where('id', '=', $id)->firstOrFail();
        } catch (\Throwable $exception) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to get role',
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
            'guard_name' => 'required',
        ]);

        try {
            $role = Roles::create([
                'name' => $request->get('name'),
                'guard_name' => $request->get('guard_name')
            ]);

            Log::info('Created role', [
                'request' => $request->all(),
                'role' => $role,
            ]);

            return new JsonResponse([
                'success' => true,
                'message' => 'Created new role',
                'data' => [
                    'role' => $role,
                ],
            ], Response::HTTP_CREATED);
        } catch (\Throwable $exception) {
            Log::error('Failed to create role', [
                'message' => $exception->getMessage(),
            ]);

            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to create role',
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
            'guard_name' => 'required',
        ]);

        try {
            $role = Roles::where('id', '=', $id)->firstOrFail();

            $role->update([
                'name' => $request->get('name'),
                'guard_name' => $request->get('guard_name'),
            ]);

            if ($request->has("permissions") && $request->post("permissions")) {
                $role->syncPermissions($request->post("permissions"));
            }

            Log::info('Updated role', [
                'request' => $request->all(),
                'role' => $role,
            ]);

            return $role;
        } catch (\Throwable $exception) {
            Log::error('Failed to update role', [
                'message' => $exception->getMessage(),
            ]);

            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to update role',
                'errors' => [
                    $exception->getMessage(),
                ],
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete($id)
    {
        try {
            $role = Roles::where('id', '=', $id)->firstOrFail();

            $role->delete();

            return new JsonResponse([], Response::HTTP_NO_CONTENT);
        } catch (\Throwable $exception) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to delete role',
                'errors' => [
                    $exception->getMessage(),
                ],
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}

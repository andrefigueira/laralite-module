<?php

namespace Modules\Laralite\Http\Controllers\Api;

use Log;
use Hash;
use App\Http\Controllers\Controller;
use Modules\Laralite\Models\Permissions;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionsController extends Controller
{
    public function get(Request $request)
    {
        $permissions = Permissions::query();
        $perPage = $request->get('perPage', 1);

        if ($request->get('all') === 'true') {
            return $permissions->get();
        }

        if ($request->input('filter') !== 'null') {
            $permissions
                ->where('name', 'LIKE', '%' . $request->input('filter') . '%')
                ->orWhere('guard_name', 'LIKE', '%' . $request->input('filter') . '%');
        }

        if ($request->input('sortBy') !== null) {
            $permissions->orderBy($request->input('sortBy'), ($request->input('sortDesc') === 'true' ? 'desc' : 'asc'));
        }

        return $permissions->paginate($perPage);
    }

    public function getOne($id)
    {
        try {
            return Permissions::where('id', '=', $id)->firstOrFail();
        } catch (\Throwable $exception) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to get permission',
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
            $permission = Permissions::create([
                'name' => $request->get('name'),
                'guard_name' => $request->get('guard_name')
            ]);

            Log::info('Created permission', [
                'request' => $request->all(),
                'permission' => $permission,
            ]);

            return new JsonResponse([
                'success' => true,
                'message' => 'Created new permission',
                'data' => [
                    'permission' => $permission,
                ],
            ], Response::HTTP_CREATED);
        } catch (\Throwable $exception) {
            Log::error('Failed to create permission', [
                'message' => $exception->getMessage(),
            ]);

            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to create permission',
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
            $permission = Permissions::where('id', '=', $id)->firstOrFail();

            $permission->update([
                'name' => $request->get('name'),
                'guard_name' => $request->get('guard_name'),
            ]);

            Log::info('Updated permission', [
                'request' => $request->all(),
                'permission' => $permission,
            ]);

            return $permission;
        } catch (\Throwable $exception) {
            Log::error('Failed to update permission', [
                'message' => $exception->getMessage(),
            ]);

            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to update permission',
                'errors' => [
                    $exception->getMessage(),
                ],
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete($id)
    {
        try {
            $permission = Permissions::where('id', '=', $id)->firstOrFail();

            $permission->delete();

            return new JsonResponse([], Response::HTTP_NO_CONTENT);
        } catch (\Throwable $exception) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to delete permission',
                'errors' => [
                    $exception->getMessage(),
                ],
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}

<?php

namespace Modules\Laralite\Http\Controllers\Api;

use Log;
use App\Http\Controllers\Controller;
use Modules\Laralite\Models\Navigation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NavigationController extends Controller
{
    public function get()
    {
        return Navigation::paginate();
    }

    public function getOne($id)
    {
        try {
            return Navigation::where('id', '=', $id)->firstOrFail();
        } catch (\Throwable $exception) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to get navigation',
                'errors' => [
                    $exception->getMessage(),
                ],
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function create(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required',
                'description' => 'required',
                'navigation' => 'required',
            ]);

            $navigation = Navigation::create([
                'name' => $request->get('name'),
                'description' => $request->get('description'),
                'navigation' => $request->get('navigation'),
            ]);

            Log::info('Created navigation', [
                'request' => $request->all(),
                'navigation' => $navigation,
            ]);

            return new JsonResponse([
                'success' => true,
                'message' => 'Created new navigation',
                'data' => [
                    'navigation' => $navigation,
                ],
            ], Response::HTTP_CREATED);
        } catch (\Throwable $exception) {
            Log::error('Failed to create navigation', [
                'message' => $exception->getMessage(),
            ]);

            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to create template',
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
            'description' => 'required',
            'navigation' => 'required',
        ]);

        try {
            $navigation = Navigation::where('id', '=', $id)->firstOrFail();

            $navigation->update([
                'name' => $request->get('name'),
                'description' => $request->get('description'),
                'navigation' => $request->get('navigation'),
            ]);

            Log::info('Updated navigation', [
                'request' => $request->all(),
                'navigation' => $navigation,
            ]);

            return $navigation;
        } catch (\Throwable $exception) {
            Log::error('Failed to update navigation', [
                'message' => $exception->getMessage(),
            ]);

            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to update navigation',
                'errors' => [
                    $exception->getMessage(),
                ],
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete($id)
    {
        try {
            $navigation = Navigation::where('id', '=', $id)->firstOrFail();

            $navigation->delete();

            return new JsonResponse([], Response::HTTP_NO_CONTENT);
        } catch (\Throwable $exception) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to delete navigation',
                'errors' => [
                    $exception->getMessage(),
                ],
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}

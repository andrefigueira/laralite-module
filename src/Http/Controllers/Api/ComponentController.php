<?php

namespace Modules\Laralite\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Laralite\Models\Component;
use Symfony\Component\HttpFoundation\Response;
use Log;

class ComponentController extends Controller
{
    public function get(Request $request)
    {
        $component = Component::query();
        $perPage = $request->get('perPage', 1);

        if ($request->get('all') === 'true') {
            return $component->get();
        }

        if ($request->input('filter') !== 'null') {
            $component->where('name', 'LIKE', '%' . $request->input('filter') . '%');
        }

        if ($request->input('sortBy') !== null) {
            $component->orderBy($request->input('sortBy'), ($request->input('sortDesc') === 'true' ? 'desc' : 'asc'));
        }

        return $component->paginate($perPage);
    }

    public function update(Request $request, $id)
    {
        try {
            $component = Component::where('id', '=', $id)->firstOrFail();

            $component->update([
                'properties' => $request->get('properties'),
            ]);

            Log::info('Updated component', [
                'request' => $request->all(),
                'component' => $component,
            ]);

            return $component;
        } catch (\Throwable $exception) {
            Log::error('Failed to update component', [
                'message' => $exception->getMessage(),
            ]);

            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to update component',
                'errors' => [
                    $exception->getMessage(),
                ],
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

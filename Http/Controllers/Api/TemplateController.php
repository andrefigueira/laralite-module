<?php

namespace Modules\Laralite\Http\Controllers\Api;

use Log;
use App\Http\Controllers\Controller;
use Modules\Laralite\Models\Template;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TemplateController extends Controller
{
    public function get(Request $request)
    {
        $templates = Template::query();
        $perPage = $request->get('perPage', 1);

        if ($request->get('all') === 'true') {
            return $templates->get();
        }

        if ($request->input('filter') !== 'null') {
            $templates
                ->where('name', 'LIKE', '%' . $request->input('filter') . '%')
                ->where('description', 'LIKE', '%' . $request->input('filter') . '%');
        }

        if ($request->input('sortBy') !== null) {
            $templates->orderBy($request->input('sortBy'), ($request->input('sortDesc') === 'true' ? 'desc' : 'asc'));
        }

        return $templates->paginate($perPage);
    }

    public function getOne($id)
    {
        try {
            return Template::where('id', '=', $id)->firstOrFail();
        } catch (\Throwable $exception) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to get template',
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
            ]);

            $template = Template::create([
                'name' => $request->get('name'),
                'module_name' => $request->get('module_name'),
                'description' => $request->get('description'),
                'background_image' => $request->get('image'),
                'sections' => $request->get('sections'),
                'header_navigation_id' => $request->get('header_navigation_id'),
                'footer_navigation_id' => $request->get('footer_navigation_id'),
            ]);

            Log::info('Created template', [
                'request' => $request->all(),
                'template' => $template,
            ]);

            return new JsonResponse([
                'success' => true,
                'message' => 'Created new template',
                'data' => [
                    'template' => $template,
                ],
            ], Response::HTTP_CREATED);
        } catch (\Throwable $exception) {
            Log::error('Failed to create template', [
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
        ]);

        try {
            $template = Template::where('id', '=', $id)->firstOrFail();

            $template->update([
                'name' => $request->get('name'),
                'module_name' => $request->get('module_name'),
                'description' => $request->get('description'),
                'background_image' => $request->get('image'),
                'sections' => $request->get('sections'),
                'header_navigation_id' => $request->get('header_navigation_id'),
                'footer_navigation_id' => $request->get('footer_navigation_id'),
            ]);

            Log::info('Updated template', [
                'request' => $request->all(),
                'template' => $template,
            ]);

            return $template;
        } catch (\Throwable $exception) {
            Log::error('Failed to update template', [
                'message' => $exception->getMessage(),
            ]);

            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to update template',
                'errors' => [
                    $exception->getMessage(),
                ],
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete($id)
    {
        try {
            $template = Template::where('id', '=', $id)->firstOrFail();

            $template->delete();

            return new JsonResponse([], Response::HTTP_NO_CONTENT);
        } catch (\Throwable $exception) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to delete template',
                'errors' => [
                    $exception->getMessage(),
                ],
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}

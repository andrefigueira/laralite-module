<?php

namespace App\Http\Controllers\Api;

use Log;
use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PageController extends Controller
{
    public function get(Request $request)
    {
        if ($request->has('with') && $request->get('with') === 'children') {
            return Page::where('parent_id', '=', null)->with('children')->with('template')->paginate();
        }

        return Page::orderBy('parent_id', 'ASC')->paginate();
    }

    public function getOne($id)
    {
        try {
            return Page::where('id', '=', $id)->with('template')->firstOrFail();
        } catch (\Throwable $exception) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to get page',
                'errors' => [
                    $exception->getMessage(),
                ],
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function create(Request $request)
    {
        try {
            $slug = $this->generateSlug($request->get('parent_id'), $request->get('slug'));

            $page = Page::create([
                'primary' => $request->get('primary'),
                'parent_id' => $request->get('parent_id'),
                'template_id' => $request->get('template_id'),
                'name' => $request->get('name'),
                'slug' => $slug,
                'components' => $request->get('components'),
                'meta' => $request->get('meta'),
            ]);

            Log::info('Created page', [
                'request' => $request->all(),
                'page' => $page,
            ]);

            return new JsonResponse([
                'success' => true,
                'message' => 'Created new page',
                'data' => [
                    'page' => $page,
                ],
            ], Response::HTTP_CREATED);
        } catch (\Throwable $exception) {
            Log::error('Failed to create page', [
                'message' => $exception->getMessage(),
            ]);

            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to create page',
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
            'slug' => 'required',
        ]);

        try {
            $page = Page::where('id', '=', $id)->firstOrFail();

            $slug = $this->generateSlug($request->get('parent_id'), $request->get('slug'));

            $page->update([
                'primary' => $request->get('primary'),
                'parent_id' => $request->get('parent_id'),
                'template_id' => $request->get('template_id'),
                'name' => $request->get('name'),
                'slug' => $slug,
                'components' => $request->get('components'),
                'meta' => $request->get('meta'),
            ]);

            Log::info('Updated page', [
                'request' => $request->all(),
                'page' => $page,
            ]);

            return $page;
        } catch (\Throwable $exception) {
            Log::error('Failed to update page', [
                'message' => $exception->getMessage(),
            ]);

            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to update page',
                'errors' => [
                    $exception->getMessage(),
                ],
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete($id)
    {
        try {
            $page = Page::where('id', '=', $id)->firstOrFail();

            $page->delete();

            return new JsonResponse([], Response::HTTP_NO_CONTENT);
        } catch (\Throwable $exception) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to delete page',
                'errors' => [
                    $exception->getMessage(),
                ],
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    private function generateSlug($parentId, $slug)
    {
        if ($parentId === null || $parentId === 0) {
            return '/' . ltrim($slug, '/');
        }

        $page = Page::where('id', '=', $parentId)->firstOrFail();

        return '/' . ltrim($page->slug, '/') . '/' . ltrim($slug, '/');
    }
}

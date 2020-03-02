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
    public function get()
    {
        return Page::paginate();
    }

    public function getOne($id)
    {
        try {
            return Page::where('id', '=', $id)->firstOrFail();
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
            $page = Page::create([
                'name' => $request->get('name'),
                'slug' => $request->get('slug'),
                'components' => $request->get('components'),
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

            $page->update([
                'name' => $request->get('name'),
                'slug' => $request->get('slug'),
                'components' => $request->get('components'),
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
}

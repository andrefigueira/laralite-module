<?php

namespace Modules\Laralite\Http\Controllers\Api;

use Log;
use App\Http\Controllers\Controller;
use Modules\Laralite\Models\ProductCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductCategoryController extends Controller
{
    public function get(Request $request)
    {
        $productCategory = ProductCategory::query();
        $perPage = $request->get('perPage', 1);

        if ($request->get('all') === 'true') {
            return $productCategory->get();
        }

        if ($request->input('filter') !== 'null') {
            $productCategory
                ->where('name', 'LIKE', '%' . $request->input('filter') . '%')
                ->orWhere('slug', 'LIKE', '%' . $request->input('filter') . '%');
        }

        if ($request->input('sortBy') !== null) {
            $productCategory->orderBy($request->input('sortBy'), ($request->input('sortDesc') === 'true' ? 'desc' : 'asc'));
        }

        return $productCategory->paginate($perPage);
    }
    public function getList(Request $request)
    {
        $categoryList = ProductCategory::where('active', '=', 1)->get();

        return $categoryList;
    }

    public function getOne($id)
    {
        try {
            return ProductCategory::where('id', '=', $id)->firstOrFail();
        } catch (\Throwable $exception) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to get product category',
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
            'slug' => 'required',
        ]);

        try {
            $productCategory = ProductCategory::create([
                'name' => $request->get('name'),
                'slug' => $request->get('slug'),
                'description' => $request->get('description'),
                'active' => $request->get('active'),
                'meta' => $request->get('meta'),
                'images' => $request->get('images'),
            ]);

            Log::info('Created product category', [
                'request' => $request->all(),
                'productCategory' => $productCategory,
            ]);

            return new JsonResponse([
                'success' => true,
                'message' => 'Created new product category',
                'data' => [
                    'productCategory' => $productCategory,
                ],
            ], Response::HTTP_CREATED);
        } catch (\Throwable $exception) {
            Log::error('Failed to create product category', [
                'message' => $exception->getMessage(),
            ]);

            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to create product category',
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
            $productCategory = ProductCategory::where('id', '=', $id)->firstOrFail();

            $productCategory->update([
                'name' => $request->get('name'),
                'slug' => $request->get('slug'),
                'description' => $request->get('description'),
                'active' => $request->get('active'),
                'meta' => $request->get('meta'),
                'images' => $request->get('images'),
            ]);

            Log::info('Updated product category', [
                'request' => $request->all(),
                'productCategory' => $productCategory,
            ]);

            return $productCategory;
        } catch (\Throwable $exception) {
            Log::error('Failed to update product category', [
                'message' => $exception->getMessage(),
            ]);

            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to update product category',
                'errors' => [
                    $exception->getMessage(),
                ],
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete($id)
    {
        try {
            $productCategory = ProductCategory::where('id', '=', $id)->firstOrFail();

            $productCategory->delete();

            return new JsonResponse([], Response::HTTP_NO_CONTENT);
        } catch (\Throwable $exception) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to delete product category',
                'errors' => [
                    $exception->getMessage(),
                ],
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}

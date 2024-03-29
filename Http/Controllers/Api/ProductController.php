<?php

namespace Modules\Laralite\Http\Controllers\Api;

use Log;
use App\Http\Controllers\Controller;
use Modules\Laralite\Http\Requests\SaveProductRequest;
use Modules\Laralite\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public function get(Request $request)
    {
        $products = Product::with(['category']);
        $perPage = $request->get('perPage', 1);

        if ($request->get('all') === 'true') {
            return $products->get();
        }

        if ($request->input('filter') !== 'null' && $request->input('filter') != '') {
            $products->whereHas('category', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->input('filter') . '%');
            })->orWhere('name', 'LIKE', '%' . $request->input('filter') . '%')
              ->orWhere('slug', 'LIKE', '%' . $request->input('filter') . '%')->get();
        }

        if ($request->input('sortBy') !== null) {
            $products->orderBy($request->input('sortBy'), ($request->input('sortDesc') === 'true' ? 'desc' : 'asc'));
        }

        return response()->json($products->orderBy('created_at', 'DESC')->paginate($perPage));
    }

    public function getProducts(Request $request)
    {
        $products = Product::with(['category'])->where('active', '=', 1)->whereHas('category', function($q){
            $q->where('slug', 'tickets');
        });

        if ($request->get('credit_purchasable_only', 0) === '1') {
            $products = $products->where('credit_purchasable', '=', 1);
        }

        return $products->get();
    }

    public function getOne($id)
    {
        try {
            return Product::where('id', '=', $id)->firstOrFail();
        } catch (\Throwable $exception) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to get product',
                'errors' => [
                    $exception->getMessage(),
                ],
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getByUrl($url)
    {
        try {
            return Product::where('slug', '=', $url)->firstOrFail();
        } catch (\Throwable $exception) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to get product',
                'errors' => [
                    $exception->getMessage(),
                ],
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function create(SaveProductRequest $request)
    {
        try {
            $product = Product::create([
                'name' => $request->input('name'),
                'slug' => $request->input('slug'),
                'description' => $request->input('description'),
                'active' =>$request->input('active'),
                'credit_purchasable' => $request->input('credit_purchasable'),
                'meta' => $request->input('meta'),
                'images' => $request->input('images'),
                'variants' => $request->input('variants'),
                'category_id' => $request->input('category_id'),
            ]);

            Log::info('Created product', [
                'request' => $request->all(),
                'product' => $product,
            ]);

            return new JsonResponse([
                'success' => true,
                'message' => 'Created new product',
                'data' => [
                    'product' => $product,
                ],
            ], Response::HTTP_CREATED);
        } catch (\Throwable $exception) {
            Log::error('Failed to create product', [
                'message' => $exception->getMessage(),
            ]);

            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to create product',
                'errors' => [
                    $exception->getMessage(),
                ],
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(SaveProductRequest $request, $id)
    {
        try {
            $product = Product::where('id', '=', $id)->firstOrFail();

            $product->update([
                'name' => $request->input('name'),
                'slug' => $request->input('slug'),
                'description' => $request->input('description'),
                'active' => $request->input('active'),
                'credit_purchasable' => $request->input('credit_purchasable'),
                'meta' => $request->input('meta'),
                'images' => $request->input('images'),
                'variants' => $request->input('variants'),
                'category_id' => $request->input('category_id'),
            ]);

            Log::info('Updated product', [
                'request' => $request->all(),
                'product' => $product,
            ]);

            return $product;
        } catch (\Throwable $exception) {
            Log::error('Failed to update product', [
                'message' => $exception->getMessage(),
            ]);

            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to update product',
                'errors' => [
                    $exception->getMessage(),
                ],
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete($id)
    {
        try {
            $product = Product::where('id', '=', $id)->firstOrFail();

            $product->delete();

            return new JsonResponse([], Response::HTTP_NO_CONTENT);
        } catch (\Throwable $exception) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to delete product',
                'errors' => [
                    $exception->getMessage(),
                ],
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}

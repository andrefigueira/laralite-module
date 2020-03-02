<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PageController extends Controller
{
    public function create(Request $request)
    {
        $page = Page::create([
            'name' => $request->get('name'),
            'slug' => $request->get('slug'),
        ]);

        if ($page) {
            return new JsonResponse([
                'success' => true,
                'message' => 'Created new page',
                'data' => [
                    'page' => $page,
                ],
            ], Response::HTTP_CREATED);
        }
    }
}

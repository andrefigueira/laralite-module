<?php

namespace Modules\Laralite\Http\Controllers\Api;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class FileController extends Controller
{
    public function imageUpload(Request $request)
    {
        $file = $request->file('file');

        $allowedImageTypes = [
            'image/jpeg',
            'image/png',
            'image/gif'
        ];

        if (!in_array($file->getMimeType(), $allowedImageTypes, true)) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Filetype (' . $file->getMimeType() . ') not allowed, must be of type ' . implode(', ', $allowedImageTypes),
            ], Response::HTTP_BAD_REQUEST);
        }

        $path = $file->storePublicly('public');

        return new JsonResponse([
            'success' => true,
            'message' => 'File Uploaded successfully',
            'data' => [
                'path' => Storage::url($path),
            ],
        ]);
    }
}

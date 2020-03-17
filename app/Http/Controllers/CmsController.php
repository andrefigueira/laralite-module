<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class CmsController extends Controller
{
    public function route(Request $request)
    {
        $requestSegments = $request->segments();
        $pageSlug = '/' . implode('/', $requestSegments);

        $page = Page::where('slug', '=', $pageSlug)->firstOrFail();

        return view('laralite.templates.' . str_replace(' ', '-', strtolower($page->template->name)), [
            'page' => $page,
        ]);
    }
}

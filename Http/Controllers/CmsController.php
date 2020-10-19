<?php

namespace Modules\Laralite\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\CradleMoney\Models\CustomerAccount;
use Modules\Laralite\Models\Page;
use Modules\Laralite\Models\User;
use Illuminate\Http\Request;
use Auth;
use Log;
use Cookie;

class CmsController extends Controller
{
    public function route(Request $request)
    {
        $this->middleware('auth:sanctum');

        $requestSegments = $request->segments();
        $pageSlug = '/' . implode('/', $requestSegments);

        Log::debug('Routing page', [
            'slug' => $pageSlug,
        ]);

        $page = Page::where('slug', '=', $pageSlug)
            ->with('template.headerNavigation')
            ->with('template.footerNavigation')
            ->firstOrFail();

        if ($page->authentication && !Auth::guard('customers')->check()) {
            Log::debug('Authentication for customer failed, redirecting to login');

            return redirect('/login');
        }

        if ($pageSlug === '/login' && Auth::guard('customers')->check()) {
            Log::debug('Authentication successful, redirecting to my-account');

            return redirect('my-account');
        }

        $template = strtolower($page->template->module_name) . '::templates.' . str_replace(' ', '-', strtolower($page->template->name));

        Log::debug('Parsed template to use for page rendering', [
            'template' => $template,
        ]);

        $page->authenticated = false;

        if (Auth::guard('customers')->check()) {
            Log::debug('Authenticated, attempting to create an access token and assigning to authed user');

            /** @var User $authedUser */
            $authedUser = Auth::guard('customers')->user();
            $authedUser->load('account');
            $page->authenticated = true;
            $page->authed_user = $authedUser;
            $page->authed_user->token = $authedUser->createToken('access-token')->plainTextToken;
        }

        return view($template, [
            'page' => $page,
        ]);
    }
}

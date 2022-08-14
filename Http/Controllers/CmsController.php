<?php

namespace Modules\Laralite\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Laralite\Models\Page;
use Modules\Laralite\Models\Settings;
use Modules\Laralite\Models\User;
use Illuminate\Http\Request;
use Auth;
use Log;
use Psy\Util\Json;

class CmsController extends Controller
{
    public function route(Request $request)
    {
        $requestSegments = $request->segments();
        $pageSlug = '/' . implode('/', $requestSegments);

        Log::debug('Routing page', [
            'slug' => $pageSlug,
        ]);

        // Check if we have any straight-up matches to the page slug
        $page = Page::where('slug', '=', $pageSlug)
            ->with('template.headerNavigation')
            ->with('template.footerNavigation')
            ->first();

        if ($page === null) {
            // We do not have a straight-up match, let's get a list of pages with defined dynamic urls and try match it
            $pagesWithDynamicUrl = Page::where('settings->dynamic_url', '=', 'true')
                ->with('template.headerNavigation')
                ->with('template.footerNavigation')
                ->get();

            $page = null;

            foreach ($pagesWithDynamicUrl as $pageWithDynamicUrl) {
                $dynamicUrlPattern = ltrim($pageWithDynamicUrl->slug, '/');
                $dynamicUrlPatternParts = explode('/', $dynamicUrlPattern);

                foreach ($dynamicUrlPatternParts as $index => $dynamicUrlPatternPart) {
                    if (strpos($dynamicUrlPatternPart, '{') !== false) {
                        if (!isset($requestSegments[$index])) {
                            continue;
                        }

                        $dynamicUrlPatternParts[$index] = $requestSegments[$index];
                    }
                }

                if ($requestSegments === $dynamicUrlPatternParts) {
                    $page = $pageWithDynamicUrl;

                    break;
                }
            }
        }

        if ($page === null) {
            $page = Page::where('slug', '=', '/404')
                ->with('template.headerNavigation')
                ->with('template.footerNavigation')
                ->first();
        }

        if (!$page) {
            return \Redirect::to('admin/login?siteNotConfigured=1');
        }

        if ($page && $page->getAttributeValue('authentication') && !Auth::guard('customers')->check()) {
            Log::debug('Authentication for customer failed, redirecting to login');
            return redirect('/login');
        }

        if ($pageSlug === '/login' && auth('customers')->check()) {
            Log::debug('Authentication successful, redirecting to my-account');

            return redirect('my-account');
        }

        $template = strtolower($page->template->module_name) . '::templates.' . str_replace(' ', '-', strtolower($page->template->name));

        Log::debug('Parsed template to use for page rendering', [
            'template' => $template,
        ]);

        $page->authenticated = false;

        if (auth()->guard('customers')->check()) {
            Log::debug('Authenticated, attempting to create an access token and assigning to authed user');

            /** @var User $authedUser */
            $authedUser = Auth::guard('customers')->user();
            $page->authenticated = true;
            $page->authed_user = $authedUser;
        }

        $settings = Settings::firstOrFail();
        return view($template, [
            'page' => $page,
            'settings' => [
                'logo' => json_decode($settings->settings, true)['siteLogo'],
                'buttonPrimaryColor' => json_decode($settings->settings, true)['buttonPrimaryColor'] ?? '',
                'buttonSecondaryColor' => json_decode($settings->settings, true)['buttonSecondaryColor'] ?? '',
                'textPrimaryColor' => json_decode($settings->settings, true)['textPrimaryColor'] ?? '',
                'textHighlightColor' => json_decode($settings->settings, true)['textHighlightColor'] ?? '',
                'currency' => json_decode($settings->settings, true)['currency'] ?? '',
                'feeAmount' => json_decode($settings->settings, true)['feeAmount'] ?? '',
                'taxActive' => json_decode($settings->settings, true)['taxActive'] ?? '',
                'taxAmount' => json_decode($settings->settings, true)['taxAmount'] ?? '',
                'serviceFeeActive' => json_decode($settings->settings, true)['serviceFeeActive'] ?? '',
                'serviceFeeAmount' => json_decode($settings->settings, true)['serviceFeeAmount'] ?? '',
                'stripePublishKey' => json_decode($settings->settings, true)['stripePublishKey'] ?? '',
            ],
        ]);
    }

    public function dynamic(Request $request)
    {

        $settings = Settings::firstOrFail();
        $settingsObject = json_decode($settings->settings, true);
        return response(
            implode(" ", [
                $this->dynamicButton($settingsObject['buttonsFont']),
                $this->dynamicHeaderFooter($settingsObject['headerFooterFont']),
                $this->dynamicText($settingsObject['mainTextFont']),
                $this->dynamicPara($settingsObject['paragraphFont'])
            ])
            , 200)
            ->header('Content-Type', 'text/css');
    }

    public function dynamicButton($font)
    {
        return ' .shop .product .add-to-basket {
                font-family: ' .json_encode($font). ', sans-serif!important;
            }
            .btn {
            font-family: ' .json_encode($font). ', sans-serif!important;
            }';

    }
    public function dynamicHeaderFooter($font)
    {
        return ' .footer ul li a {
                    font-family: ' .json_encode($font). ', sans-serif!important;
                   }
                   
                   .top-nav .top-nav-menu li a {
                    font-family: ' .json_encode($font). ', sans-serif!important;
                   }
                   
                   .logo-link {
                   font-family:' .json_encode($font). ', sans-serif!important;
               }';
    }
    public function dynamicText($font)
    {
        return 'h3 {
                   font-family: ' .json_encode($font). ', sans-serif!important;
               }';
    }

    public function dynamicPara($font)
    {
        return ' .form-text {
                   font-family:' .json_encode($font). ', sans-serif!important;
               }
               label {
                   font-family:' .json_encode($font). ', sans-serif!important;
               }
               p {
                   font-family:' .json_encode($font). ', sans-serif!important;
               }
               td {
                   font-family:' .json_encode($font). ', sans-serif!important;
               }
               .form-control {
                   font-family:' .json_encode($font). ', sans-serif!important;
               }';
    }

}

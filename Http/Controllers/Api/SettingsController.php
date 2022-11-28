<?php

namespace Modules\Laralite\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Log;
use Modules\Laralite\Models\Settings;
use Redirect;
use Symfony\Component\HttpFoundation\Response;

class SettingsController extends Controller
{
    public function update(Request $request)
    {
        $stripeSecretKey = $request->get('stripeSecretKey');
        $stripeAccessToken = $request->get('stripeAccessToken');
        $stripeClientId = $request->get('stripeClientId');
        $stripeAccountId = $request->get('stripeAccountId');
        $stripeLiveAccount = $request->get('stripeLiveAccount');
        $stripePublishKey = $request->get('stripePublishKey');
        $currency = $request->get('currency');
        $feeActive = $request->get('feeActive');
        $feeAmount = $request->get('feeAmount');
        $taxActive = $request->get('taxActive');
        $taxAmount = $request->get('taxAmount');
        $serviceFeeActive = $request->get('serviceFeeActive');
        $serviceFeeAmount = $request->get('serviceFeeAmount');
        $siteLogo = $request->get('siteLogo');
        $buttonPrimaryColor = $request->get('buttonPrimaryColor');
        $buttonSecondaryColor = $request->get('buttonSecondaryColor');
        $textPrimaryColor = $request->get('textPrimaryColor');
        $textHighlightColor = $request->get('textHighlightColor');
        $buttonsFont = $request->get('buttonsFont');
        $headerFooterFont = $request->get('headerFooterFont');
        $paragraphFont = $request->get('paragraphFont');
        $mainTextFont = $request->get('mainTextFont');
        $maintenanceActive = $request->get('maintenanceActive');


        if($maintenanceActive) {
            // Create a new file
            Storage::put(App::storagePath() . '/framework/down', json_encode([
                "time" => 1643352441,
                "message" => 'Site is Down',
                "retry" => null,
                "allowed" => [],
            ]));
        } else {
            if (Storage::exists(App::storagePath() . '/framework/down')) {
                unlink( App::storagePath() . '/framework/down');
            }
        }

        $settings = [
            'currency' => $currency,
            'stripeSecretKey' => $stripeSecretKey,
            'stripeClientId' => $stripeClientId,
            'stripeAccessToken' => $stripeAccessToken,
            'stripeAccountId' => $stripeAccountId,
            'stripeLiveAccount' => $stripeLiveAccount,
            'stripePublishKey' => $stripePublishKey,
            'feeActive' => $feeActive,
            'feeAmount' => $feeAmount,
            'taxActive' => $taxActive,
            'taxAmount' => $taxAmount,
            'serviceFeeActive' => $serviceFeeActive,
            'serviceFeeAmount' => $serviceFeeAmount ? bcmul($serviceFeeAmount, 100) : '0',
            'siteLogo'  => $siteLogo,
            'buttonPrimaryColor'    =>  $buttonPrimaryColor,
            'textPrimaryColor'     =>  $textPrimaryColor,
            'buttonSecondaryColor'     =>  $buttonSecondaryColor,
            'textHighlightColor'     =>  $textHighlightColor,
            'buttonsFont' => $buttonsFont,
            'headerFooterFont' => $headerFooterFont,
            'paragraphFont' => $paragraphFont,
            'mainTextFont' => $mainTextFont,
            'maintenanceActive' => $maintenanceActive
        ];

        try {
            Settings::updateOrCreate(['id' => 1], [
                'active' => 1,
                'settings' => $settings
            ]);

            Log::info('Settings updated', [
                'request' => $request->all(),
            ]);

            return new JsonResponse([
                'success' => true,
                'message' => 'Settings updated sucessfully',
            ], Response::HTTP_CREATED);
        } catch (\Throwable $exception) {
            Log::error('Failed to update settings', [
                'message' => $exception->getMessage(),
            ]);

            return new JsonResponse([
                'success' => false,
                'message' => 'Failed to update settings',
                'errors' => [
                    $exception->getMessage(),
                ],
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $request->get('stripeSecretKey');
    }

    public function stripeConnect(Request $request)
    {
        if ($request->has('error')) {
            return new JsonResponse([
                'success' => false,
                'message' => $request->get('error'),
                'errors' => [
                    $request->get('error_description'),
                ],
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $this->validate($request, [
            'scope' => 'required',
            'code' => 'required',
        ]);


        try {
            $settings = Settings::firstOrFail();
            $settingsValue = json_decode($settings->settings);
            // Set your secret key. Remember to switch to your live secret key in production!
            // See your keys here: https://dashboard.stripe.com/account/apikeys
            \Stripe\Stripe::setApiKey($settingsValue->stripeSecretKey);

            $stripeResponse = \Stripe\OAuth::token([
                'grant_type' => 'authorization_code',
                'code' => $request->get('code'),
            ]);

            $settingsValue->stripeAccountId = $stripeResponse->stripe_user_id;
            $settingsValue->stripeAccessToken = $stripeResponse->access_token;
            $settingsValue->stripeLiveAccount = $stripeResponse->livemode;
            $settings->settings = json_encode($settingsValue);
            $settings->save();

            return Redirect::to('admin/settings');
        } catch (\Throwable $exception) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Error trying to get auth token for connected account',
                'errors' => [
                    $exception->getMessage(),
                ],
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

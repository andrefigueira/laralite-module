<?php

namespace Modules\Laralite\Http\Controllers\Api;

use Log;
use Hash;
use Redirect;
use App\Http\Controllers\Controller;
use Modules\Laralite\Models\Settings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Stripe\StripeClient;
use Symfony\Component\HttpFoundation\Response;

class SettingsController extends Controller
{
    public function update(Request $request) 
    {
        $connectedStripeAccount = $request->get('connectedStripeAccount');
        $currency = $request->get('currency');
        $feeActive = $request->get('feeActive');
        $feeAmount = $request->get('feeAmount');
        $siteLogo = $request->get('siteLogo');
        $buttonPrimaryColor = $request->get('buttonPrimaryColor');
        $buttonSecondaryColor = $request->get('buttonSecondaryColor');
        $textPrimaryColor = $request->get('textPrimaryColor');
        $textHighlightColor = $request->get('textHighlightColor');
        $buttonsFont = $request->get('buttonsFont');
        $headerFooterFont = $request->get('headerFooterFont');
        $paragraphFont = $request->get('paragraphFont');
        $mainTextFont = $request->get('mainTextFont');


        $settings = [
            'currency' => $currency,
            'connectedStripeAccount' => $connectedStripeAccount,
            'feeActive' => $feeActive,
            'feeAmount' => $feeAmount,
            'siteLogo'  => $siteLogo,
            'buttonPrimaryColor'    =>  $buttonPrimaryColor,
            'textPrimaryColor'     =>  $textPrimaryColor,
            'buttonSecondaryColor'     =>  $buttonSecondaryColor,
            'textHighlightColor'     =>  $textHighlightColor,
            'buttonsFont' => $buttonsFont,
            'headerFooterFont' => $headerFooterFont,
            'paragraphFont' => $paragraphFont,
            'mainTextFont' => $mainTextFont,
        ];

        try {
            Settings::updateOrCreate(['id' => 1], [
                'active' => 1,
                'settings' => json_encode($settings)
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

        return $request->get('connectedStripeAccount');
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
            // Set your secret key. Remember to switch to your live secret key in production!
            // See your keys here: https://dashboard.stripe.com/account/apikeys
            \Stripe\Stripe::setApiKey('sk_test_51HdwipCYDc7HSRjalZglpakY5as37lC76mOmho2RKGcqYhNf3IcJFi20PcIbPVV9HEXbX9QyZ7BRybYCI5FDI01t00CCj0k2yK');

            $stripeResponse = \Stripe\OAuth::token([
                'grant_type' => 'authorization_code',
                'code' => $request->get('code'),
            ]);

            // Access the connected account id in the response
            $connectedAccountId = $stripeResponse->stripe_user_id;

            return Redirect::to('admin/settings?connectedAccountId=' . $connectedAccountId);
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

<?php

namespace Modules\Laralite\Providers;

use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use Modules\Laralite\Services\SettingsService;
use Modules\Laralite\Services\StripeService;
use Stripe\Stripe;
use Stripe\StripeClient;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(StripeService::class, function (Application $app) {
            $settingsService = $app->make(SettingsService::class);
            $key = $settingsService->getStripeKey() ?: 'UNSET_STRIPE_KEY';
            $stripeClient = new StripeClient($key);
            return new StripeService($stripeClient);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Passport::hashClientSecrets();

        $this->commands([
            \Modules\Laralite\Console\ReportDeploy::class,
            \Modules\Laralite\Console\RefreshComponents::class,
            \Modules\Laralite\Console\RefreshTemplates::class,
            \Modules\Laralite\Console\CreateUser::class,
        ]);
    }
}

<?php

namespace Modules\Laralite\Providers;

use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use Modules\Laralite\Console\CreateUser;
use Modules\Laralite\Console\ProcessOrdersImport;
use Modules\Laralite\Console\RefreshComponents;
use Modules\Laralite\Console\RefreshTemplates;
use Modules\Laralite\Console\ReportDeploy;
use Modules\Laralite\Console\SubscriptionCharge;
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
            $stripeClient = new StripeClient($settingsService->getStripeKey());
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
            ReportDeploy::class,
            RefreshComponents::class,
            RefreshTemplates::class,
            CreateUser::class,
            SubscriptionCharge::class,
            ProcessOrdersImport::class,
        ]);
    }
}

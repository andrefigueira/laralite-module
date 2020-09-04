<?php

namespace Modules\Laralite\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

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
        ]);
    }
}

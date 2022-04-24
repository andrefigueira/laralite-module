<?php

namespace Modules\Laralite\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Laralite\Models\Customer\Subscription;
use Modules\Laralite\Observers\CustomerSubscriptionObserver;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //Subscription::observe(CustomerSubscriptionObserver::class);
    }
}

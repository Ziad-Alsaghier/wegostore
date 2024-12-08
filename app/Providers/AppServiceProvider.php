<?php

namespace App\Providers;

use App\Models\Extra;
use App\Models\Plan;
use App\Models\Store;
use App\Models\User;
use App\Observers\admin\plan\PlanObserver;
use App\Observers\User\UserObserver;
use Illuminate\Support\ServiceProvider;
use App\Observers\admin\store\StoreObserver;
use App\Observers\ExtraObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
     
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Start Make Observer
        Plan::observe(PlanObserver::class);
        User::observe(UserObserver::class);
        Store::observe(StoreObserver::class);
       Extra::observe(ExtraObserver::class);
    }
}

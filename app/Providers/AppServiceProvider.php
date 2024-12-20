<?php

namespace App\Providers;

use App\Models\Activity;
use App\Models\Extra;
use App\Models\Plan;
use App\Models\Store;
use App\Models\Tutorial;
use App\Models\TutorialGroup;
use App\Models\User;
use App\Observers\ActivityObserver;
use App\Observers\admin\plan\PlanObserver;
use App\Observers\User\UserObserver;
use Illuminate\Support\ServiceProvider;
use App\Observers\admin\store\StoreObserver;
use App\Observers\ExtraObserver;
use App\Observers\TutorialGroupObserver;
use App\Observers\TutorialObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind the Plesk configuration
        $this->app->bind('plesk', function () {
            return config('plesk');
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Start Observers
        User::observe(UserObserver::class);
        Store::observe(StoreObserver::class);
        Extra::observe(ExtraObserver::class);
        Plan::observe(PlanObserver::class);
        Activity::observe(ActivityObserver::class);
        TutorialGroup::observe(TutorialGroupObserver::class);
    }
}

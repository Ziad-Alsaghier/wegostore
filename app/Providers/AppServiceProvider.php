<?php

namespace App\Providers;

use App\Models\Store;
use App\Models\User;
use App\Observers\User\UserObserver;
use Illuminate\Support\ServiceProvider;
use App\Observers\admin\store\StoreObserver;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
          $this->app->singleton(User::class, function () {
          return new User();
          });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Start Make Observer
        User::observe(UserObserver::class);
        Store::observe(StoreObserver::class);
      
    }
}

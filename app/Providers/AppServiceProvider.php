<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\Grab68GuzzleHelper;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('grab68', function () {
            return new Grab68GuzzleHelper();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

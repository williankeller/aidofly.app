<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;

class GlobalAuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $authUser = Auth::user();

        // Share the Authenticated User globally
        View::composer('*', function ($view) use ($authUser) {
            $view->with('authUser', $authUser);
        });

        $this->app->singleton('authUser', function ($app) use ($authUser) {
            return $authUser;
        });
    }
}

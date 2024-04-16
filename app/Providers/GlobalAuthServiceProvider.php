<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        // Share the Authenticated User globally
        View::composer('*', function ($view) {
            $view->with('authUser', Auth::user());
        });

        // Alternatively, you can use a singleton if needed elsewhere
        $this->app->singleton('authUser', function ($app) {
            return Auth::user();
        });
    }
}

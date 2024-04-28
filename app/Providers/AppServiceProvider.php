<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use OpenAI\Client as OpenAIClient;
use OpenAI;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('OpenAI\Client', function ($app) {
            return OpenAI::factory()
                ->withApiKey(env('OPENAI_API_KEY'))
                ->make();
        });

        // If there are interfaces, bind them here
        $this->app->bind('OpenAI\Contracts\TransporterContract', 'OpenAI\Transporter');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

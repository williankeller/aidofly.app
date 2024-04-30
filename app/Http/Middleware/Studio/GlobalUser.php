<?php

namespace App\Http\Middleware\Studio;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class GlobalUser
{
    public function handle($request, Closure $next)
    {
        // Share the Authenticated User globally
        View::share('authUser', Auth::user());

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware\Studio;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class GlobalUser
{
    public function handle($request, Closure $next)
    {
        $authUser = Auth::user();
    
        // Check if the cookie `token` is set
        $token = $request->cookie('token');
        if ($authUser && !$token) {
            auth()->logout();
            return redirect()->route('auth.signin');
        }

        // Share the Authenticated User globally
        View::share('authUser', $authUser);

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware\Studio;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminAuth
{
    public function handle($request, Closure $next)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user || !$user->isAdministrator()) {
            logger()->warning('Unauthorized access', [
                'user' => $user ? $user->uuid : null,
                'route' => $request->route()->getName()
            ]);
            abort(404, 'Unauthorized access');
        }

        return $next($request);
    }
}

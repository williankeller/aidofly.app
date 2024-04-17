<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\Auth\AuthToken;

class Authenticate
{

    public function __construct(
        private AuthToken $token
    ) {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get the token from the request header
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Authorization Token not provided'], 401);
        }

        try {
            $credentials = $this->token->validate($token)->getUser();

            auth()->setUser($credentials);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unauthorized: ' . $e->getMessage()], 401);
        }

        return $next($request);
    }
}

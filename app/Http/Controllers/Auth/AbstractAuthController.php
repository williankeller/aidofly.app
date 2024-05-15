<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\AbstractController;
use App\Services\Auth\AuthToken;
use Illuminate\Support\Facades\Cookie;

abstract class AbstractAuthController extends AbstractController
{
    public function __construct(
        private AuthToken $token
    ) {
    }

    protected function setAuthCookieToken(?int $expires = null): void
    {
        if ($expires === null) {
            $expires = 60 * 24 * 365;
        }

        $jwtToken = $this->token->generate(
            auth()->user()->uuid,
            $expires
        );

        Cookie::queue(Cookie::make(
            AuthToken::COOKIE_NAME, // Cookie name
            $jwtToken,              // Cookie value
            $expires,               // Expiration time (in minutes, 1 year)
            null,                   // Path (use default config)
            null,                   // Secure (use default config)
            null,                   // Secure (only transmitted over HTTPS)
            false,                  // HttpOnly (false, so it can be accessed via JavaScript)
            false,                  // Raw (whether the cookie value should be sent as is)
            'Strict'                // SameSite (same-site cookie policy)
        ));
    }

    protected function removeAuthCookieToken(): void
    {
        Cookie::queue(Cookie::forget(AuthToken::COOKIE_NAME));
    }
}

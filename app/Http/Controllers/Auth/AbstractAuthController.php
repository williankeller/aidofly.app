<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\AbstractController;
use App\Services\Auth\AuthToken;

abstract class AbstractAuthController extends AbstractController
{
    public function __construct(
        private AuthToken $token
    ) {
    }

    protected function setAuthCookieToken(?int $expires = null): void
    {
        if ($expires === null) {
            $expires = 365 * 24 * 60 * 60;
        }

        $jwtToken = $this->token->generate(
            auth()->user()->uuid,
            $expires
        );

        cookie()->queue(AuthToken::COOKIE_NAME, $jwtToken, $expires, null, null, null, false);
    }

    protected function removeAuthCookieToken(): void
    {
        cookie()->queue(cookie()->forget(AuthToken::COOKIE_NAME));
    }
}

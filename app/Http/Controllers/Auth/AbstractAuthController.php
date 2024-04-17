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

    protected function setAuthCookieToken($expires = 86400): void
    {
        $jwtToken = $this->token->generate(
            auth()->user()->uuid,
            $expires
        );

        cookie()->queue(AuthToken::COOKIE_NAME, $jwtToken, $expires, null, null, null, false);
    }
}

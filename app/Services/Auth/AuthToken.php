<?php

namespace App\Services\Auth;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\User;
use stdClass;

/**
 * Class Token
 * Handles the JWT token
 */
class AuthToken
{
    const COOKIE_NAME = 'token';

    private string $key;

    private User $user;

    private stdClass $decoded;

    public function __construct()
    {
        $this->key = env('JWT_TOKEN');
    }

    public function generate(string $uuid, $expires = 86400): string
    {
        $payload = [
            "iss" => env('APP_URL'),
            "aud" => env('APP_URL'),
            "iat" => time(),
            "exp" => time() + $expires,
            "sub" => "1",
            "uuid" => $uuid
        ];

        return JWT::encode($payload, $this->key, 'HS256');
    }

    public function decode(string $token): AuthToken
    {
        try {
            $this->decoded = JWT::decode($token, new Key($this->key, 'HS256'));
        } catch (\Exception $e) {
            throw new \Exception("Invalid token");
        }
        return $this;
    }

    public function validate(string $token): AuthToken
    {
        $decoded = $this->decode($token)->getToken();

        if ($decoded->exp < time()) {
            throw new \Exception("Token expired");
        }

        if ($decoded->iss !== env('APP_URL')) {
            throw new \Exception("Invalid domain");
        }

        try {
            $this->user = User::where('uuid', $decoded->uuid)->firstOrFail();
        } catch (\Exception $e) {
            throw new \Exception("Invalid user");
        }

        return $this;
    }

    public function getToken(): stdClass
    {
        return $this->decoded;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}

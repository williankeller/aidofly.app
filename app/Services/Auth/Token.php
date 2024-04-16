<?php

namespace App\Services\Auth;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * Class Token
 * Handles the JWT token
 */
class Token
{
    private string $key;

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

    public function decode(string $token)
    {
        return JWT::decode($token, new Key($this->key, 'HS256'));
    }

    public function validate(string $token): bool
    {
        $decoded = $this->decode($token);

        return $decoded->exp > time();
    }

    public function getUuid(string $token): ?string
    {
        $decoded = $this->decode($token);

        if ($decoded->exp > time()) {
            return null;
        }
        return $decoded->uuid;
    }
}

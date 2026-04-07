<?php

namespace App\Service;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTService
{
    private string $secretKey;

    public function __construct(string $jwtSecret = 'your-secret-key-change-in-production')
    {
        $this->secretKey = $jwtSecret;
    }

    public function generateToken(int $userId, string $email, array $roles = []): string
    {
        $now = new \DateTime();
        $expiration = (new \DateTime())->modify('+1 hour');

        $payload = [
            'iat' => $now->getTimestamp(),
            'exp' => $expiration->getTimestamp(),
            'userId' => $userId,
            'email' => $email,
            'roles' => $roles,
        ];

        return JWT::encode($payload, $this->secretKey, 'HS256');
    }

    public function validateToken(string $token): ?array
    {
        try {
            $decoded = JWT::decode($token, new Key($this->secretKey, 'HS256'));
            return (array) $decoded;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getSecretKey(): string
    {
        return $this->secretKey;
    }
}

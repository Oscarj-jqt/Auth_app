<?php 

/**
 * Service JWT
 */

namespace App\Service;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTService
{
    private string $secret;
    private string $issuer;
    private int $ttl;

    public function __construct(string $secret, string $issuer = 'app', int $ttl = 3600)
    {
        $this->secret = $secret;
        $this->issuer = $issuer;
        $this->ttl = $ttl;
    }

    public function encode(array $payload): string
    {
        $now = time();
        $payload = array_merge([
            'iss' => $this->issuer,
            'iat' => $now,
            'exp' => $now + $this->ttl
        ], $payload);

        return JWT::encode($payload, $this->secret, 'HS256');
    }

    public function decode(string $jwt): ?array
    {
        try {
            $decoded = JWT::decode($jwt, new Key($this->secret, 'HS256'));
            // Convert stdClass to array
            return json_decode(json_encode($decoded), true);
        } catch (\Exception $e) {
            return null;
        }
    }
}
<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use RuntimeException;

class FirebaseAuthService
{
    protected const GOOGLE_PUBLIC_KEYS_URL = 'https://www.googleapis.com/robot/v1/metadata/x509/securetoken@system.gserviceaccount.com';

    public function __construct(
        protected ?string $projectId = null
    ) {
        $this->projectId = $this->projectId ?: config('services.firebase.project_id');
    }

    /**
     * Verify a Firebase ID Token (JWT) and return decoded claims.
     *
     * @return array{
     *     uid: string,
     *     email: ?string,
     *     name: ?string,
     *     picture: ?string,
     *     email_verified: bool,
     *     phone_number: ?string,
     *     claims: array<string, mixed>
     * }
     *
     * @throws InvalidArgumentException|RuntimeException
     */
    public function verifyIdToken(string $idToken): array
    {
        $idToken = trim($idToken);
        if (empty($idToken)) {
            throw new InvalidArgumentException('Firebase ID token is required.');
        }

        $tokenParts = explode('.', $idToken);
        if (count($tokenParts) !== 3) {
            throw new InvalidArgumentException('Malformed Firebase ID token structure.');
        }

        $headerJson = base64_decode(strtr($tokenParts[0], '-_', '+/'));
        $header = json_decode($headerJson, true);

        if (! is_array($header) || empty($header['kid']) || ($header['alg'] ?? '') !== 'RS256') {
            throw new InvalidArgumentException('Invalid Firebase ID token header or unsupported algorithm.');
        }

        $kid = $header['kid'];
        $publicKeys = $this->getGooglePublicKeys();

        if (! isset($publicKeys[$kid])) {
            // Invalidate cache and retry once in case Google rotated keys
            Cache::forget('firebase_google_public_keys');
            $publicKeys = $this->getGooglePublicKeys();

            if (! isset($publicKeys[$kid])) {
                throw new InvalidArgumentException('Firebase ID token signed with unknown public key ID.');
            }
        }

        $publicKeyPem = $publicKeys[$kid];

        try {
            $decoded = JWT::decode($idToken, new Key($publicKeyPem, 'RS256'));
        } catch (\Throwable $e) {
            Log::warning('Firebase JWT decoding failed: '.$e->getMessage());
            throw new InvalidArgumentException('Invalid or expired Firebase ID token: '.$e->getMessage(), 0, $e);
        }

        $payload = (array) $decoded;

        // Verify Firebase security claims
        $projectId = $this->projectId;
        $now = time();

        if ($projectId) {
            $expectedIssuer = 'https://securetoken.google.com/'.$projectId;
            if (($payload['iss'] ?? '') !== $expectedIssuer) {
                throw new InvalidArgumentException("Token issuer mismatch. Expected {$expectedIssuer}");
            }

            if (($payload['aud'] ?? '') !== $projectId) {
                throw new InvalidArgumentException("Token audience mismatch. Expected {$projectId}");
            }
        }

        if (empty($payload['sub']) || ! is_string($payload['sub'])) {
            throw new InvalidArgumentException('Token subject (Firebase UID) must be a non-empty string.');
        }

        if (isset($payload['auth_time']) && $payload['auth_time'] > ($now + 300)) {
            throw new InvalidArgumentException('Token auth_time is in the future.');
        }

        return [
            'uid' => (string) $payload['sub'],
            'email' => isset($payload['email']) ? (string) $payload['email'] : null,
            'name' => isset($payload['name']) ? (string) $payload['name'] : null,
            'picture' => isset($payload['picture']) ? (string) $payload['picture'] : null,
            'email_verified' => (bool) ($payload['email_verified'] ?? false),
            'phone_number' => isset($payload['phone_number']) ? (string) $payload['phone_number'] : null,
            'claims' => $payload,
        ];
    }

    /**
     * Retrieve Google's public certificates used to sign Firebase ID tokens.
     *
     * @return array<string, string>
     */
    public function getGooglePublicKeys(): array
    {
        return Cache::remember('firebase_google_public_keys', now()->addHours(6), function () {
            $response = Http::timeout(10)->get(self::GOOGLE_PUBLIC_KEYS_URL);

            if (! $response->successful()) {
                throw new RuntimeException('Unable to fetch Google Firebase public keys.');
            }

            $keys = $response->json();
            if (! is_array($keys) || empty($keys)) {
                throw new RuntimeException('Google Firebase public keys response is empty or invalid.');
            }

            return $keys;
        });
    }
}

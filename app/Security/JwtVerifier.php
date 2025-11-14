<?php

declare(strict_types=1);

namespace App\Security;

use App\Contracts\Security\JwtVerifierContract;
use App\Exceptions\ApiException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use Illuminate\Support\Facades\File;

class JwtVerifier implements JwtVerifierContract
{
    private string $publicKeyPath;

    private array $algorithms;

    private int $leeway;

    public function __construct()
    {
        $this->publicKeyPath = config('jwt.public_key_path');
        $this->algorithms    = config('jwt.algorithms', ['RS256']);
        $this->leeway        = config('jwt.leeway', 60);
    }

    /**
     * Verify and decode a JWT token.
     *
     * @throws ApiException
     */
    public function verify(string $jwt): array
    {
        $publicKey = $this->loadPublicKey();

        JWT::$leeway = $this->leeway;

        try {
            $decoded = JWT::decode(
                $jwt,
                new Key($publicKey, $this->algorithms[0])
            );

            return $this->convertToArray($decoded);
        } catch (SignatureInvalidException $e) {
            throw new ApiException('Invalid token signature', 401);
        } catch (BeforeValidException $e) {
            throw new ApiException('Token not yet valid', 401);
        } catch (ExpiredException $e) {
            throw new ApiException('Token has expired', 401);
        } catch (\UnexpectedValueException $e) {
            throw new ApiException('Malformed token: '.$e->getMessage(), 401);
        } catch (\DomainException $e) {
            throw new ApiException('Token verification failed: '.$e->getMessage(), 401);
        }
    }

    /**
     * Load the public key from the configured path.
     *
     * @throws ApiException
     */
    private function loadPublicKey(): string
    {
        if (! File::exists($this->publicKeyPath)) {
            throw new ApiException(
                'JWT public key not found. Please configure JWT_PUBLIC_KEY_PATH.',
                500
            );
        }

        $publicKey = File::get($this->publicKeyPath);

        if (empty($publicKey)) {
            throw new ApiException('JWT public key is empty', 500);
        }

        return $publicKey;
    }

    /**
     * Convert decoded JWT object to associative array.
     */
    private function convertToArray(object $decoded): array
    {
        return json_decode(json_encode($decoded), true);
    }
}
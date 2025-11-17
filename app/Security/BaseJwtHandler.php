<?php

declare(strict_types=1);

namespace App\Security;

use Firebase\JWT\JWT;
use App\Exceptions\ApiException;
use Illuminate\Support\Facades\File;
use App\Contracts\Security\JwtKeyLoaderContract;

abstract class BaseJwtHandler implements JwtKeyLoaderContract
{
    protected string $publicKeyPath;

    protected string $privateKeyPath;

    protected array $algorithms;

    protected int $leeway;

    public function __construct()
    {
        $this->publicKeyPath = config('jwt.public_key_path');
        $this->privateKeyPath = config('jwt.private_key_path');
        $this->algorithms = config('jwt.algorithms', ['RS256']);
        $this->leeway = config('jwt.leeway', 60);

        JWT::$leeway = $this->leeway;
    }

    /**
     * Load the private key from the configured path.
     *
     * @throws ApiException
     */
    public function loadPrivateKey(): string
    {
        return $this->loadKey(
            $this->privateKeyPath,
            'JWT private key not found. Please configure JWT_PRIVATE_KEY_PATH.',
            'JWT private key is empty'
        );
    }

    /**
     * Load the public key from the configured path.
     *
     * @throws ApiException
     */
    public function loadPublicKey(): string
    {
        return $this->loadKey(
            $this->publicKeyPath,
            'JWT public key not found. Please configure JWT_PUBLIC_KEY_PATH.',
            'JWT public key is empty'
        );
    }

    /**
     * Load a key file from the specified path.
     *
     * @throws ApiException
     */
    private function loadKey(string $path, string $notFoundMessage, string $emptyMessage): string
    {
        if (! File::exists($path)) {
            throw new ApiException($notFoundMessage, 500);
        }

        $key = File::get($path);

        if (empty($key)) {
            throw new ApiException($emptyMessage, 500);
        }

        return $key;
    }

    /**
     * Convert decoded JWT object to associative array.
     */
    protected function convertToArray(object $decoded): array
    {
        return json_decode(json_encode($decoded), true);
    }

    /**
     * Get the primary algorithm for JWT operations.
     */
    public function getAlgorithm(): string
    {
        return $this->algorithms[0];
    }
}

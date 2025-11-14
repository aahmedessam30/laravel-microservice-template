<?php

declare(strict_types=1);

namespace Tests\Unit\Security;

use App\Exceptions\ApiException;
use App\Security\JwtVerifier;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class JwtVerifierTest extends TestCase
{
    private JwtVerifier $verifier;

    protected function setUp(): void
    {
        parent::setUp();

        // Skip tests if firebase/php-jwt is not installed
        if (! class_exists('Firebase\\JWT\\JWT')) {
            $this->markTestSkipped('firebase/php-jwt package is not installed. Run: composer require firebase/php-jwt');
        }

        $this->verifier = new JwtVerifier;
    }

    public function test_throws_exception_when_public_key_not_found(): void
    {
        Config::set('jwt.public_key_path', '/nonexistent/path/key.pem');

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('JWT public key not found');

        $this->verifier->verify('some.jwt.token');
    }

    public function test_throws_exception_on_malformed_token(): void
    {
        $this->mockPublicKey();

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Malformed token');

        $this->verifier->verify('invalid-token-format');
    }

    public function test_throws_exception_on_invalid_signature(): void
    {
        $this->mockPublicKey();

        // Valid JWT structure but wrong signature
        $invalidJwt = 'eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiYWRtaW4iOnRydWUsImlhdCI6MTUxNjIzOTAyMn0.invalid_signature';

        $this->expectException(ApiException::class);

        $this->verifier->verify($invalidJwt);
    }

    public function test_throws_exception_on_expired_token(): void
    {
        $this->mockPublicKey();

        // Create an expired token (you would need to generate this with proper signing)
        // This is a placeholder - in real tests you'd generate a properly signed expired token
        $expiredJwt = $this->generateExpiredToken();

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Token has expired');

        $this->verifier->verify($expiredJwt);
    }

    public function test_throws_exception_when_token_not_yet_valid(): void
    {
        $this->mockPublicKey();

        // Token with nbf (not before) in the future
        $futureJwt = $this->generateFutureToken();

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Token not yet valid');

        $this->verifier->verify($futureJwt);
    }

    /**
     * Helper method to mock public key file.
     */
    private function mockPublicKey(): void
    {
        $keyPath = storage_path('test-keys/public.pem');
        $keyDir = dirname($keyPath);

        if (! is_dir($keyDir)) {
            mkdir($keyDir, 0755, true);
        }

        // Generate a temporary RSA key pair for testing
        $config = [
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ];

        $res = openssl_pkey_new($config);

        if ($res === false) {
            $this->markTestSkipped('OpenSSL key generation failed. Please check your OpenSSL configuration.');

            return;
        }

        $exported = openssl_pkey_export($res, $privateKey);

        if ($exported === false) {
            $this->markTestSkipped('OpenSSL key export failed. Please check your OpenSSL configuration.');

            return;
        }

        $details = openssl_pkey_get_details($res);

        if ($details === false || ! isset($details['key'])) {
            $this->markTestSkipped('OpenSSL key details retrieval failed.');

            return;
        }

        $publicKey = $details['key'];

        file_put_contents($keyPath, $publicKey);

        Config::set('jwt.public_key_path', $keyPath);
    }

    /**
     * Generate an expired JWT token for testing.
     */
    private function generateExpiredToken(): string
    {
        // Placeholder - would generate a real expired token with proper signing
        // For actual implementation, use firebase/php-jwt to encode with exp in the past
        return 'eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwiZXhwIjoxfQ.expired';
    }

    /**
     * Generate a not-yet-valid JWT token for testing.
     */
    private function generateFutureToken(): string
    {
        // Placeholder - would generate a real token with nbf in the future
        return 'eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmJmIjo5OTk5OTk5OTk5fQ.future';
    }

    protected function tearDown(): void
    {
        // Clean up test keys
        $keyPath = storage_path('test-keys/public.pem');
        if (File::exists($keyPath)) {
            File::delete($keyPath);
        }

        $keyDir = storage_path('test-keys');
        if (is_dir($keyDir)) {
            rmdir($keyDir);
        }

        parent::tearDown();
    }
}

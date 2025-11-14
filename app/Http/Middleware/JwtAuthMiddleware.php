<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Contracts\Security\JwtVerifierContract;
use App\Exceptions\ApiException;
use App\Traits\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JwtAuthMiddleware
{
    use ApiResponse;

    public function __construct(
        private readonly JwtVerifierContract $jwtVerifier
    ) {}

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $token = $this->extractBearerToken($request);

            if (! $token) {
                throw new ApiException('Missing authorization token', 401);
            }

            $payload = $this->jwtVerifier->verify($token);

            $request->attributes->set('jwt_payload', $payload);
            $request->attributes->set('user_id', $payload['sub'] ?? null);

            return $next($request);
        } catch (ApiException $e) {
            return $this->error(
                $e->getMessage(),
                $e->getCode(),
                $e->getErrors()
            );
        }
    }

    /**
     * Extract Bearer token from Authorization header.
     */
    private function extractBearerToken(Request $request): ?string
    {
        $header = $request->header('Authorization');

        if (! $header) {
            return null;
        }

        if (! str_starts_with($header, 'Bearer ')) {
            return null;
        }

        $token = substr($header, 7);

        return empty($token) ? null : $token;
    }
}
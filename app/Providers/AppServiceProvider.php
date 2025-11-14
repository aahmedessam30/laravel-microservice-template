<?php

namespace App\Providers;

use App\Contracts\Security\JwtVerifierContract;
use App\Contracts\Services\OpenApiServiceContract;
use App\Security\JwtVerifier;
use App\Services\OpenApiService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(OpenApiServiceContract::class, OpenApiService::class);
        $this->app->bind(JwtVerifierContract::class, JwtVerifier::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

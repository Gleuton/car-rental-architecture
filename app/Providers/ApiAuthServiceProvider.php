<?php

declare(strict_types=1);

namespace App\Providers;

use App\Core\ApiAuth\Domain\Services\TokenAuthServiceInterface;
use App\Core\ApiAuth\Infra\Auth\JwtTokenAuthService;
use Illuminate\Support\ServiceProvider;

class ApiAuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            TokenAuthServiceInterface::class,
            JwtTokenAuthService::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

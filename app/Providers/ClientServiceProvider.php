<?php

declare(strict_types=1);

namespace App\Providers;

use App\Core\Client\Domain\Repositories\ClientRepositoryInterface;
use App\Core\Client\Infra\Persistence\EloquentClientRepository;
use Illuminate\Support\ServiceProvider;

class ClientServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            ClientRepositoryInterface::class,
            EloquentClientRepository::class
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

<?php

declare(strict_types=1);

namespace App\Providers;

use App\Core\Car\Domain\Repositories\CarRepositoryInterface;
use App\Core\Car\Infra\EloquentCarRepository;
use Illuminate\Support\ServiceProvider;

class CarServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            CarRepositoryInterface::class,
            EloquentCarRepository::class
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

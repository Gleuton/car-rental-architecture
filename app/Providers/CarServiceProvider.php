<?php

declare(strict_types=1);

namespace App\Providers;

use App\Core\Car\Domain\Repositories\CarModelRepositoryInterface;
use App\Core\Car\Domain\Repositories\CarRepositoryInterface;
use App\Core\Car\Infra\Persistence\EloquentCarModelRepository;
use App\Core\Car\Infra\Persistence\EloquentCarRepository;
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

        $this->app->bind(
            CarModelRepositoryInterface::class,
            EloquentCarModelRepository::class
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

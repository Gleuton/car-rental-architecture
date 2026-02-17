<?php

declare(strict_types=1);

namespace App\Providers;

use App\Core\CarModel\Domain\Repositories\CarModelRepositoryInterface;
use App\Core\CarModel\Infra\EloquentCarModelRepository;
use Illuminate\Support\ServiceProvider;

class CarModelServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
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

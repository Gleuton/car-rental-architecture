<?php

declare(strict_types=1);

namespace App\Providers;

use App\Core\Rental\Domain\Repositories\RentalRepositoryInterface;
use App\Core\Rental\Infra\Persistence\EloquentRentalRepository;
use Illuminate\Support\ServiceProvider;

class RentalServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            RentalRepositoryInterface::class,
            EloquentRentalRepository::class
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

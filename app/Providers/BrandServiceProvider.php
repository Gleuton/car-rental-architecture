<?php

declare(strict_types=1);

namespace App\Providers;

use App\Core\Brand\Infra\Persistence\EloquentBrandRepository;
use App\Core\Car\Domain\Repositories\BrandRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class BrandServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            BrandRepositoryInterface::class,
            EloquentBrandRepository::class
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

<?php

namespace App\Providers;

use App\Core\Brand\Domain\Repositories\BrandRepositoryInterface;
use App\Core\Brand\Infra\Persistence\EloquentBrandRepository;
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

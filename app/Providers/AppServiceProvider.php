<?php

namespace App\Providers;

use App\Core\Shared\Domain\Storage\FileStorageInterface;
use App\Core\Shared\Infra\Storage\LocalStorage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            FileStorageInterface::class,
            LocalStorage::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

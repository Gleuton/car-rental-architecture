<?php

declare(strict_types=1);

namespace App\Providers;

use App\Core\Shared\Domain\Identity\UuidGeneratorInterface;
use App\Core\Shared\Domain\Storage\FileStorageInterface;
use App\Core\Shared\Infra\Identity\LaravelUuidGenerator;
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

        $this->app->bind(
            UuidGeneratorInterface::class,
            LaravelUuidGenerator::class
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

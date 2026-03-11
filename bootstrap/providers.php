<?php

declare(strict_types=1);
use App\Providers\AppServiceProvider;
use App\Providers\BrandServiceProvider;
use App\Providers\CarModelServiceProvider;
use App\Providers\CarServiceProvider;
use App\Providers\ClientServiceProvider;
use App\Providers\RentalServiceProvider;
use Tymon\JWTAuth\Providers\LaravelServiceProvider;

return [
    AppServiceProvider::class,
    BrandServiceProvider::class,
    CarModelServiceProvider::class,
    CarServiceProvider::class,
    ClientServiceProvider::class,
    RentalServiceProvider::class,
    LaravelServiceProvider::class,
];

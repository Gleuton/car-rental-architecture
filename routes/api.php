<?php

declare(strict_types=1);

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\CarModelController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\RentalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', static function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:api'], static function () {
    Route::apiResource('clients', ClientController::class);
    Route::apiResource('cars', CarController::class);
    Route::apiResource('rentals', RentalController::class);
    Route::apiResource('brands', BrandController::class);
    Route::apiResource('car-models', CarModelController::class);

    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::post('/refresh', [AuthController::class, 'refresh']);

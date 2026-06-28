<?php

declare(strict_types=1);

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Web\BrandModelsPageController;
use App\Http\Controllers\Web\BrandPageController;
use App\Http\Controllers\Web\CarPageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/marcas', [BrandPageController::class, 'index'])->name('web.brands.index');
Route::get('/modelos', [BrandModelsPageController::class, 'index'])->name('web.car.models.index');
Route::get('/cars', [CarPageController::class, 'index'])->name('web.car.index');

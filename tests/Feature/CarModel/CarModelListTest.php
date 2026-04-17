<?php

declare(strict_types=1);

use App\Models\CarModel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

uses(RefreshDatabase::class);

it('can list car models', function () {
    Auth::guard('api')->login(User::factory()->create());
    CarModel::factory()->count(20)->create();

    $response = $this->getJson('/api/car-models');

    $response->assertSuccessful()
        ->assertJsonCount(15, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'uuid',
                    'brandUuid',
                    'name',
                    'image',
                    'doorsNumber',
                    'seatsNumber',
                    'airbags',
                    'abs',
                ],
            ],
            'meta' => [
                'current_page',
                'per_page',
                'total',
                'last_page',
            ],
        ]);
});

it('can search car models by name', function () {
    Auth::guard('api')->login(User::factory()->create());
    CarModel::factory()->create(['name' => 'Corolla']);
    CarModel::factory()->create(['name' => 'Civic']);

    $response = $this->getJson('/api/car-models?search=corolla');

    $response->assertSuccessful()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', 'Corolla');
});

it('can paginate car models', function () {
    Auth::guard('api')->login(User::factory()->create());
    CarModel::factory()->count(20)->create();

    $response = $this->getJson('/api/car-models?per_page=5&page=2');

    $response->assertSuccessful()
        ->assertJsonCount(5, 'data')
        ->assertJsonPath('meta.current_page', 2)
        ->assertJsonPath('meta.per_page', 5);
});

it('returns 401 when listing car models without authentication', function () {
    $response = $this->getJson('/api/car-models');
    $response->assertStatus(401);
});

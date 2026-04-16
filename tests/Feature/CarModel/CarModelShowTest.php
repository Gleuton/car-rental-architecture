<?php

declare(strict_types=1);

use App\Models\CarModel;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can show a model car', function () {
    authenticateApi();
    /** @var CarModel $carModel */
    $carModel = CarModel::factory()->create();

    $response = $this->getJson("/api/car-models/$carModel->id");

    $response->assertStatus(200)
        ->assertJsonPath('data.id', fn ($id) => is_int($id))
        ->assertJsonPath('data.uuid', fn ($uuid) => is_string($uuid))
        ->assertJsonPath('data.brandId', $carModel->brand_id)
        ->assertJsonPath('data.name', $carModel->name)
        ->assertJsonPath('data.image', $carModel->image)
        ->assertJsonPath('data.doorsNumber', $carModel->doors)
        ->assertJsonPath('data.seatsNumber', $carModel->seats)
        ->assertJsonPath('data.airbags', $carModel->airbags)
        ->assertJsonPath('data.abs', $carModel->abs);
});

it('returns 404 when tray show non-existent ModelCar', function () {
    authenticateApi();
    $response = $this->getJson('/api/car-models/999');

    $response->assertStatus(404);
});

it('returns 401 when showing a car model without authentication', function () {
    $carModel = CarModel::factory()->create();
    $response = $this->getJson("/api/car-models/$carModel->id");
    $response->assertStatus(401);
});

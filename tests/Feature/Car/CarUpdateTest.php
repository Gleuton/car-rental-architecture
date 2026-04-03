<?php

declare(strict_types=1);

use App\Models\Car;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can update allowed car data', function () {
    authenticateApi();

    /** @var Car $carEloquent */
    $carEloquent = Car::factory()->create();

    $response = $this->putJson('/api/cars/'.$carEloquent->id, [
        'license_plate' => 'ABC-123',
        'color' => 'red',
        'is_available' => false,
    ]);

    $response->assertStatus(200)
        ->assertJsonPath('data.licensePlate', 'ABC-123')
        ->assertJsonPath('data.color', 'red')
        ->assertJsonPath('data.isAvailable', false)
        ->assertJsonPath('data.carModelId', $carEloquent->car_model_id)
        ->assertJsonPath('data.km', $carEloquent->km);

    $this->assertDatabaseHas('cars', [
        'id' => $carEloquent->id,
        'car_model_id' => $carEloquent->car_model_id,
        'license_plate' => 'ABC-123',
        'color' => 'red',
        'is_available' => 0,
        'km' => $carEloquent->km,
    ]);
});

it('can update license plate to the same value', function () {
    authenticateApi();

    $licensePlate = 'ABC-123';

    /** @var Car $carEloquent */
    $carEloquent = Car::factory()->create([
        'license_plate' => $licensePlate,
    ]);

    $response = $this->putJson('/api/cars/'.$carEloquent->id, [
        'license_plate' => $licensePlate,
    ]);

    $response->assertStatus(200)
        ->assertJsonPath('data.licensePlate', $licensePlate);

    $this->assertDatabaseHas('cars', [
        'id' => $carEloquent->id,
        'license_plate' => $licensePlate,
    ]);
});

it('cant update the license plate to one that is already in use.', function () {
    authenticateApi();

    $newLicensePlate = 'ABC-123';

    /** @var Car $carEloquent */
    $carEloquent = Car::factory()->create(['license_plate' => 'ABC-124']);

    Car::factory()->create(['license_plate' => $newLicensePlate]);

    $response = $this->putJson('/api/cars/'.$carEloquent->id, [
        'license_plate' => $newLicensePlate,
    ]);

    $response->assertStatus(409)
        ->assertJsonPath('message', 'Car with this license plate already exists')
        ->assertJsonPath('domain', 'car')
        ->assertJsonPath('type', 'DOMAIN_ERROR')
        ->assertJsonPath('code', 'ALREADY_EXISTS')
        ->assertJsonPath('app_code', 6009);

    $this->assertDatabaseHas('cars', $carEloquent->toArray());
});

it('returns 401 when updating a car without authentication', function () {
    $car = Car::factory()->create();
    $response = $this->putJson('/api/cars/'.$car->id, ['color' => 'blue']);
    $response->assertStatus(401);
});

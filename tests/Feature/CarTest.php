<?php

declare(strict_types=1);

use App\Models\Car;
use App\Models\CarModel;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a Car', function () {
    /** @var CarModel $carModel */
    $carModel = CarModel::factory()->create();

    $data = [
        'car_model_id' => $carModel->id,
        'license_plate' => 'ABC-1234',
        'color' => 'red',
        'is_available' => true,
        'km' => 1000,
    ];
    $response = $this->postJson('/api/cars', $data);
    $response->assertStatus(200);
    $response->assertJsonPath('data.licensePlate', 'ABC-1234');

    $response->assertJsonPath('data.id', fn ($id) => is_int($id));

    $this->assertDatabaseHas('cars', $data);
});

it('validates required fields when creating a Car', function () {
    $response = $this->postJson('/api/cars', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'car_model_id',
            'license_plate',
            'color',
        ]);
});

it('validates license_plate max length when creating a Car', function () {
    /** @var CarModel $carModel */
    $carModel = CarModel::factory()->create();

    $data = [
        'car_model_id' => $carModel->id,
        'license_plate' => 'ABC12345678901',
        'color' => 'red',
        'is_available' => true,
        'km' => 1000,
    ];

    $response = $this->postJson('/api/cars', $data);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['license_plate']);
});

it('validates color max length when creating a Car', function () {
    /** @var CarModel $carModel */
    $carModel = CarModel::factory()->create();

    $data = [
        'car_model_id' => $carModel->id,
        'license_plate' => 'ABC-1234',
        'color' => str_repeat('a', 256),
        'is_available' => true,
        'km' => 1000,
    ];

    $response = $this->postJson('/api/cars', $data);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['color']);
});

it('validates km must be a non-negative integer when creating a Car', function () {
    /** @var CarModel $carModel */
    $carModel = CarModel::factory()->create();

    $data = [
        'car_model_id' => $carModel->id,
        'license_plate' => 'ABC-1234',
        'color' => 'red',
        'is_available' => true,
        'km' => -100,
    ];

    $response = $this->postJson('/api/cars', $data);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['km']);
});

it('validates is_available must be a boolean when creating a Car', function () {
    /** @var CarModel $carModel */
    $carModel = CarModel::factory()->create();

    $data = [
        'car_model_id' => $carModel->id,
        'license_plate' => 'ABC-1234',
        'color' => 'red',
        'is_available' => 'invalid',
        'km' => 1000,
    ];

    $response = $this->postJson('/api/cars', $data);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['is_available']);
});

it('validates car_model_id must be an integer when creating a Car', function () {
    $data = [
        'car_model_id' => 'invalid',
        'license_plate' => 'ABC-1234',
        'color' => 'red',
        'is_available' => true,
        'km' => 1000,
    ];

    $response = $this->postJson('/api/cars', $data);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['car_model_id']);
});

it('creates a Car with default is_available and km values when not provided', function () {
    /** @var CarModel $carModel */
    $carModel = CarModel::factory()->create();

    $data = [
        'car_model_id' => $carModel->id,
        'license_plate' => 'ABC-1234',
        'color' => 'red',
    ];

    $response = $this->postJson('/api/cars', $data);

    $response->assertStatus(200);
    $response->assertJsonPath('data.isAvailable', true);
    $response->assertJsonPath('data.km', 0);

    $this->assertDatabaseHas('cars', [
        'car_model_id' => $carModel->id,
        'license_plate' => 'ABC-1234',
        'color' => 'red',
        'is_available' => true,
        'km' => 0,
    ]);
});

it('returns appropriate error when car_model_id does not exist', function () {
    $data = [
        'car_model_id' => 999,
        'license_plate' => 'ABC-1234',
        'color' => 'red',
        'is_available' => true,
        'km' => 1000,
    ];

    $response = $this->postJson('/api/cars', $data);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['car_model_id']);
});

it('validates license_plate min length when creating a Car', function () {
    /** @var CarModel $carModel */
    $carModel = CarModel::factory()->create();

    $data = [
        'car_model_id' => $carModel->id,
        'license_plate' => 'ABC123',
        'color' => 'red',
        'is_available' => true,
        'km' => 1000,
    ];

    $response = $this->postJson('/api/cars', $data);

    $response->assertStatus(409)
        ->assertJson([
            'type' => 'DOMAIN_ERROR',
            'code' => 'LICENSE_PLATE_TOO_SHORT',
            'message' => 'License plate must have at least 7 characters',
        ]);
});

it('validates color min length when creating a Car', function () {
    /** @var CarModel $carModel */
    $carModel = CarModel::factory()->create();

    $data = [
        'car_model_id' => $carModel->id,
        'license_plate' => 'ABC-1234',
        'color' => 'ab',
        'is_available' => true,
        'km' => 1000,
    ];

    $response = $this->postJson('/api/cars', $data);

    $response->assertStatus(409)
        ->assertJson([
            'type' => 'DOMAIN_ERROR',
            'code' => 'COLOR_TOO_SHORT',
            'message' => 'Color must have at least 3 characters',
        ]);
});

it('validates license_plate accepts exactly 7 characters', function () {
    /** @var CarModel $carModel */
    $carModel = CarModel::factory()->create();

    $data = [
        'car_model_id' => $carModel->id,
        'license_plate' => 'ABC1234',
        'color' => 'red',
        'is_available' => true,
        'km' => 1000,
    ];

    $response = $this->postJson('/api/cars', $data);

    $response->assertStatus(200)
        ->assertJsonPath('data.licensePlate', 'ABC1234');
});

it('validates license_plate accepts exactly 10 characters', function () {
    /** @var CarModel $carModel */
    $carModel = CarModel::factory()->create();

    $data = [
        'car_model_id' => $carModel->id,
        'license_plate' => 'ABC-123456',
        'color' => 'red',
        'is_available' => true,
        'km' => 1000,
    ];

    $response = $this->postJson('/api/cars', $data);

    $response->assertStatus(200)
        ->assertJsonPath('data.licensePlate', 'ABC-123456');
});

it('validates color accepts exactly 3 characters', function () {
    /** @var CarModel $carModel */
    $carModel = CarModel::factory()->create();

    $data = [
        'car_model_id' => $carModel->id,
        'license_plate' => 'ABC-1234',
        'color' => 'red',
        'is_available' => true,
        'km' => 1000,
    ];

    $response = $this->postJson('/api/cars', $data);

    $response->assertStatus(200)
        ->assertJsonPath('data.color', 'red');
});

it('validates color accepts exactly 50 characters', function () {
    /** @var CarModel $carModel */
    $carModel = CarModel::factory()->create();

    $color = str_repeat('A', 50);
    $data = [
        'car_model_id' => $carModel->id,
        'license_plate' => 'ABC-1234',
        'color' => $color,
        'is_available' => true,
        'km' => 1000,
    ];

    $response = $this->postJson('/api/cars', $data);

    $response->assertStatus(200)
        ->assertJsonPath('data.color', $color);
});

it('accepts zero km when creating a Car', function () {
    /** @var CarModel $carModel */
    $carModel = CarModel::factory()->create();

    $data = [
        'car_model_id' => $carModel->id,
        'license_plate' => 'ABC-1234',
        'color' => 'red',
        'is_available' => true,
        'km' => 0,
    ];

    $response = $this->postJson('/api/cars', $data);

    $response->assertStatus(200)
        ->assertJsonPath('data.km', 0);
});

it('rejects duplicate license_plate when creating a Car', function () {
    /** @var CarModel $carModel */
    $carModel = CarModel::factory()->create();

    $data = [
        'car_model_id' => $carModel->id,
        'license_plate' => 'ABC-1234',
        'color' => 'red',
        'is_available' => true,
        'km' => 1000,
    ];

    $this->postJson('/api/cars', $data)->assertStatus(200);

    $response = $this->postJson('/api/cars', $data);

    $response->assertStatus(409)
        ->assertJson([
            'type' => 'DOMAIN_ERROR',
            'code' => 'ALREADY_EXISTS',
            'message' => 'Car with this license plate already exists',
        ]);
});

it('can show a car', function () {
    /** @var Car $car */
    $car = Car::factory()->create([
        'license_plate' => 'ABC-1234',
        'color' => 'red',
        'is_available' => true,
        'km' => 1000,
    ]);

    $response = $this->getJson('/api/cars/'.$car->id);
    $response->assertStatus(200)
        ->assertJsonPath('data.licensePlate', 'ABC-1234');
});

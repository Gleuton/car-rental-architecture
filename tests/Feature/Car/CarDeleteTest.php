<?php

declare(strict_types=1);

use App\Models\Car;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can delete a car', function () {
    authenticateApi();
    /** @var Car $car */
    $car = Car::factory()->create();

    $response = $this->deleteJson('/api/cars/'.$car->id);

    $response->assertStatus(204);

    $this->assertDatabaseMissing('cars', [
        'id' => $car->id,
    ]);
});

it('returns 404 when deleting a non-existent car', function () {
    authenticateApi();
    $response = $this->deleteJson('/api/cars/999');

    $response->assertStatus(404);
});

it('returns 401 when deleting a car without authentication', function () {
    $car = Car::factory()->create();
    $response = $this->deleteJson('/api/cars/'.$car->id);
    $response->assertStatus(401);
});

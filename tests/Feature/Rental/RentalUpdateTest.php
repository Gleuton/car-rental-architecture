<?php

declare(strict_types=1);

use App\Models\Car;
use App\Models\Client;
use App\Models\Rental;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

it('can update rental data', function () {
    authenticateApi();

    /** @var Rental $rental */
    $rental = Rental::factory()->create([
        'day_price_cents' => 5000,
        'start_date' => '2026-03-01 08:00:00',
        'end_date' => '2026-03-05 08:00:00',
        'initial_km' => 1000,
        'final_km' => 1500,
    ]);

    $response = $this->putJson('/api/rentals/'.$rental->uuid, [
        'day_price_cents' => 7000,
        'start_date' => '2026-03-10 08:00:00',
        'end_date' => '2026-03-12 08:00:00',
        'initial_km' => 2000,
        'final_km' => 2200,
    ]);

    $response->assertStatus(200)
        ->assertJsonPath('data.id', $rental->id)
        ->assertJsonPath('data.uuid', fn ($uuid) => Str::isUuid($uuid))
        ->assertJsonPath('data.dayPriceCents', 7000)
        ->assertJsonPath('data.startDate', '2026-03-10 08:00:00')
        ->assertJsonPath('data.endDate', '2026-03-12 08:00:00')
        ->assertJsonPath('data.initialKm', 2000)
        ->assertJsonPath('data.finalKm', 2200);

    $this->assertDatabaseHas('rentals', [
        'id' => $rental->id,
        'car_uuid' => $rental->car->uuid,
        'client_uuid' => $rental->client->uuid,
        'day_price_cents' => 7000,
        'start_date' => '2026-03-10 08:00:00',
        'end_date' => '2026-03-12 08:00:00',
        'initial_km' => 2000,
        'final_km' => 2200,
    ]);
});

it('can partially update rental data', function () {
    authenticateApi();

    /** @var Rental $rental */
    $rental = Rental::factory()->create([
        'day_price_cents' => 5000,
        'start_date' => '2026-03-01 08:00:00',
        'end_date' => '2026-03-05 08:00:00',
    ]);

    $response = $this->putJson('/api/rentals/'.$rental->uuid, [
        'day_price_cents' => 8000,
    ]);

    $response->assertStatus(200)
        ->assertJsonPath('data.dayPriceCents', 8000)
        ->assertJsonPath('data.startDate', '2026-03-01 08:00:00')
        ->assertJsonPath('data.endDate', '2026-03-05 08:00:00');
});

it('can update rental using car_uuid and client_uuid', function () {
    authenticateApi();

    /** @var Rental $rental */
    $rental = Rental::factory()->create();
    /** @var Car $newCar */
    $newCar = Car::factory()->create();
    /** @var Client $newClient */
    $newClient = Client::factory()->create();

    $response = $this->putJson('/api/rentals/'.$rental->uuid, [
        'car_uuid' => $newCar->uuid,
        'client_uuid' => $newClient->uuid,
    ]);

    $response->assertStatus(200)
        ->assertJsonPath('data.carId', $newCar->id)
        ->assertJsonPath('data.clientId', $newClient->id);

    $this->assertDatabaseHas('rentals', [
        'id' => $rental->id,
        'car_id' => $newCar->id,
        'car_uuid' => $newCar->uuid,
        'client_id' => $newClient->id,
        'client_uuid' => $newClient->uuid,
    ]);
});

it('returns 404 when updating non-existent rental', function () {
    authenticateApi();

    $response = $this->putJson('/api/rentals/'.(string) Str::uuid(), [
        'day_price_cents' => 7000,
    ]);

    $response->assertStatus(404);
});

it('returns validation error for invalid update payload', function () {
    authenticateApi();

    /** @var Rental $rental */
    $rental = Rental::factory()->create();

    $response = $this->putJson('/api/rentals/'.$rental->uuid, [
        'start_date' => 'invalid-date',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['start_date']);
});

it('returns 401 when updating a rental without authentication', function () {
    $rental = Rental::factory()->create();
    $response = $this->putJson('/api/rentals/'.$rental->uuid, ['day_price_cents' => 7000]);
    $response->assertStatus(401);
});

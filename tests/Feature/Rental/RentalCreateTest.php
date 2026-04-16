<?php

declare(strict_types=1);

use App\Models\Car;
use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

it('can create a Rental', function () {
    authenticateApi();
    /** @var Client $cliente */
    $cliente = Client::factory()->create();
    /** @var Car $car */
    $car = Car::factory()->create();

    $startDate = now()->toDateTime();
    $endDate = now()->addDays(30)->toDateTime();
    $dayPrice = 5000;
    $initialKm = 1000;
    $finalKm = 1500;

    $data = [
        'client_id' => $cliente->id,
        'car_id' => $car->id,
        'start_date' => $startDate->format('Y-m-d H:i:s'),
        'end_date' => $endDate->format('Y-m-d H:i:s'),
        'day_price_cents' => $dayPrice,
        'initial_km' => $initialKm,
        'final_km' => $finalKm,
    ];

    $response = $this->postJson('/api/rentals', $data);

    $response->assertStatus(201)
        ->assertJsonPath('data.id', fn ($id) => is_int($id))
        ->assertJsonPath('data.uuid', fn ($uuid) => Str::isUuid($uuid))
        ->assertJsonPath('data.startDate', $startDate->format('Y-m-d H:i:s'))
        ->assertJsonPath('data.endDate', $endDate->format('Y-m-d H:i:s'))
        ->assertJsonPath('data.dayPriceCents', $dayPrice)
        ->assertJsonPath('data.initialKm', $initialKm)
        ->assertJsonPath('data.finalKm', $finalKm);

    $this->assertDatabaseHas('rentals', [
        'client_id' => $cliente->id,
        'client_uuid' => $cliente->uuid,
        'car_id' => $car->id,
        'car_uuid' => $car->uuid,
        'start_date' => $startDate,
        'end_date' => $endDate,
        'day_price_cents' => $dayPrice,
        'initial_km' => $initialKm,
        'final_km' => $finalKm,
    ]);

    $rental = DB::table('rentals')->where('client_id', $cliente->id)->where('car_id', $car->id)->first();

    expect($rental)->not->toBeNull()
        ->and(Str::isUuid($rental->uuid))->toBeTrue()
        ->and($rental->car_uuid)->toBe($car->uuid)
        ->and($rental->client_uuid)->toBe($cliente->uuid);
});

it('can create a Rental using car_uuid and client_uuid', function () {
    authenticateApi();
    /** @var Client $client */
    $client = Client::factory()->create();
    /** @var Car $car */
    $car = Car::factory()->create();

    $payload = [
        'client_uuid' => $client->uuid,
        'car_uuid' => $car->uuid,
        'start_date' => now()->format('Y-m-d H:i:s'),
        'end_date' => now()->addDay()->format('Y-m-d H:i:s'),
        'day_price_cents' => 3500,
        'initial_km' => 100,
        'final_km' => 160,
    ];

    $response = $this->postJson('/api/rentals', $payload);

    $response->assertStatus(201)
        ->assertJsonPath('data.clientId', $client->id)
        ->assertJsonPath('data.carId', $car->id);

    $this->assertDatabaseHas('rentals', [
        'client_id' => $client->id,
        'client_uuid' => $client->uuid,
        'car_id' => $car->id,
        'car_uuid' => $car->uuid,
    ]);
});

it('returns 401 when creating a rental without authentication', function () {
    $client = Client::factory()->create();
    $car = Car::factory()->create();
    $response = $this->postJson('/api/rentals', [
        'client_id' => $client->id,
        'car_id' => $car->id,
        'start_date' => now()->format('Y-m-d H:i:s'),
        'end_date' => now()->addDays(1)->format('Y-m-d H:i:s'),
        'day_price_cents' => 5000,
        'initial_km' => 1000,
        'final_km' => 1500,
    ]);
    $response->assertStatus(401);
});

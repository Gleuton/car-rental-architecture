<?php

declare(strict_types=1);

use App\Models\Car;
use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a Rental', function () {
    /** @var Client $cliente */
    $cliente = Client::factory()->create();
    /** @var Car $car */
    $car = Car::factory()->create();

    $startDate = now()->toDateString();
    $endDate = now()->addDays(30)->toDateString();
    $dayPrice = 5000;
    $initialKm = 1000;
    $finalKm = 1500;

    $data = [
        'client_id' => $cliente->id,
        'car_id' => $car->id,
        'start_date' => $startDate,
        'end_date' => $endDate,
        'day_price_cents' => $dayPrice,
        'initial_km' => $initialKm,
        'final_km' => $finalKm,
    ];

    $response = $this->postJson('/api/rentals', $data);

    $response->assertStatus(201)
        ->assertJsonPath('data.id', fn ($id) => is_int($id))
        ->assertJsonPath('data.start_date', $startDate)
        ->assertJsonPath('data.end_date', $endDate)
        ->assertJsonPath('data.day_price_cents', $dayPrice)
        ->assertJsonPath('data.initial_km', $initialKm)
        ->assertJsonPath('data.final_km', $finalKm);

    $this->assertDatabaseHas('rentals', [
        'client_id' => $cliente->id,
        'car_id' => $car->id,
        'start_date' => $startDate,
        'end_date' => $endDate,
        'day_price_cents' => $dayPrice,
        'initial_km' => $initialKm,
        'final_km' => $finalKm,
    ]);
});

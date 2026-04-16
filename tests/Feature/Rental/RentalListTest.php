<?php

declare(strict_types=1);

use App\Models\Rental;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can list all rentals returning with price calculated', function () {
    authenticateApi();

    Rental::factory()->create([
        'start_date' => '2026-03-01 08:00:00',
        'end_date' => '2026-03-05 08:00:00',
        'day_price_cents' => 5000,
    ]);

    Rental::factory()->create([
        'day_price_cents' => 10000,
        'start_date' => '2026-03-03 08:00:00',
        'end_date' => '2026-03-04 08:00:00',
    ]);

    $response = $this->getJson('/api/rentals');

    $response->assertOk()
        ->assertJsonCount(2, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'uuid', 'carId', 'clientId', 'startDate', 'endDate', 'totalPrice', 'initialKm', 'finalKm'],
            ],
            'meta' => [
                'current_page',
                'per_page',
                'total',
                'last_page',
            ],
        ]);

    expect($response->json('data.0.uuid'))->not->toBeNull();

    $response->assertJsonPath('data.0.totalPrice', 200);
    $response->assertJsonPath('data.1.totalPrice', 100);
});

it('filters rentals by start_date range', function () {
    authenticateApi();

    Rental::factory()->create([
        'start_date' => '2026-03-01 08:00:00',
        'end_date' => '2026-03-02 08:00:00',
    ]);

    Rental::factory()->create([
        'start_date' => '2026-03-10 08:00:00',
        'end_date' => '2026-03-11 08:00:00',
    ]);

    $response = $this->getJson('/api/rentals?start_date_from=2026-03-05 00:00:00');

    $response->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('meta.total', 1);
});

it('applies default ordering by start_date', function () {
    authenticateApi();

    $lateRental = Rental::factory()->create([
        'start_date' => '2026-03-10 08:00:00',
        'end_date' => '2026-03-11 08:00:00',
    ]);

    $earlyRental = Rental::factory()->create([
        'start_date' => '2026-03-01 08:00:00',
        'end_date' => '2026-03-02 08:00:00',
    ]);

    $response = $this->getJson('/api/rentals');

    $response->assertOk();

    expect($response->json('data.0.id'))->toBe($earlyRental->id)
        ->and($response->json('data.1.id'))->toBe($lateRental->id);
});

it('can paginate rentals', function () {
    authenticateApi();

    Rental::factory(20)->create([
        'start_date' => '2026-03-01 08:00:00',
        'end_date' => '2026-03-05 18:00:00',
    ]);

    $response = $this->getJson('/api/rentals?per_page=5&page=2');

    $response->assertOk()
        ->assertJsonCount(5, 'data')
        ->assertJsonPath('meta.current_page', 2)
        ->assertJsonPath('meta.per_page', 5)
        ->assertJsonPath('meta.total', 20)
        ->assertJsonPath('meta.last_page', 4);
});

it('validates index filters', function () {
    authenticateApi();

    $response = $this->getJson('/api/rentals?start_date_from=invalid-date');

    $response->assertStatus(422);
});

it('returns 401 when listing rentals without authentication', function () {
    $response = $this->getJson('/api/rentals');
    $response->assertStatus(401);
});

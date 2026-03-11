<?php

declare(strict_types=1);

use App\Models\Rental;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can show a rental by id', function () {
    authenticateApi();
    /** @var Rental $rental */
    $rental = Rental::factory()->create();

    $response = $this->getJson("/api/rentals/{$rental->id}");

    $response->assertOk()
        ->assertJsonPath('data.id', $rental->id);
});

it('returns 404 when rental is not found', function () {
    authenticateApi();
    $response = $this->getJson('/api/rentals/999999');

    $response->assertStatus(404);
});

it('returns 401 when showing a rental without authentication', function () {
    $rental = Rental::factory()->create();
    $response = $this->getJson("/api/rentals/{$rental->id}");
    $response->assertStatus(401);
});

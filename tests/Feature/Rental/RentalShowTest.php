<?php

declare(strict_types=1);

use App\Models\Rental;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

it('can show a rental by uuid', function () {
    authenticateApi();
    /** @var Rental $rental */
    $rental = Rental::factory()->create();

    $response = $this->getJson("/api/rentals/{$rental->uuid}");

    $response->assertOk()
        ->assertJsonPath('data.id', $rental->id)
        ->assertJsonPath('data.uuid', fn ($uuid) => Str::isUuid($uuid));
});

it('returns 404 when rental is not found', function () {
    authenticateApi();
    $response = $this->getJson('/api/rentals/'.(string) Str::uuid());

    $response->assertStatus(404);
});

it('returns 401 when showing a rental without authentication', function () {
    $rental = Rental::factory()->create();
    $response = $this->getJson("/api/rentals/{$rental->uuid}");
    $response->assertStatus(401);
});

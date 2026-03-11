<?php

declare(strict_types=1);

use App\Models\Rental;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can delete a rental', function () {
    authenticateApi();
    /** @var Rental $rental */
    $rental = Rental::factory()->create();

    $response = $this->deleteJson('/api/rentals/'.$rental->id);

    $response->assertStatus(204);

    $this->assertDatabaseMissing('rentals', [
        'id' => $rental->id,
    ]);
});

it('returns 404 when deleting a non-existent rental', function () {
    authenticateApi();
    $response = $this->deleteJson('/api/rentals/999');

    $response->assertStatus(404);
});

it('returns 401 when deleting a rental without authentication', function () {
    $rental = Rental::factory()->create();
    $response = $this->deleteJson('/api/rentals/'.$rental->id);
    $response->assertStatus(401);
});

<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can logout and invalidate current token', function () {
    $user = User::factory()->create();
    authenticateApi($user);

    $logoutResponse = $this->postJson('/api/logout');

    $logoutResponse->assertStatus(204);

    $meResponse = $this->getJson('/api/me');

    $meResponse->assertStatus(401)
        ->assertJsonPath('message', 'Unauthenticated.');
});

it('returns 401 when trying to logout without authentication', function () {
    $response = $this->postJson('/api/logout');

    $response->assertStatus(401)
        ->assertJsonPath('message', 'Unauthenticated.');
});

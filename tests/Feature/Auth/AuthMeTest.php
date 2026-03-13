<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns authenticated user basic data', function () {
    $user = User::factory()->create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ]);

    authenticateApi($user);

    $response = $this->getJson('/api/me');

    $response->assertOk()
        ->assertJsonStructure([
            'data' => ['id', 'name', 'email'],
        ])
        ->assertJsonPath('data.id', $user->id)
        ->assertJsonPath('data.name', 'John Doe')
        ->assertJsonPath('data.email', 'john@example.com');
});

it('returns 401 when requesting me without authentication', function () {
    $response = $this->getJson('/api/me');

    $response->assertStatus(401);
});

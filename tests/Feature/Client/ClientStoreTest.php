<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

uses(RefreshDatabase::class);

it('can create a client', function () {
    Auth::guard('api')->login(User::factory()->create());

    $response = $this->postJson('/api/clients', [
        'name' => 'John Doe',
    ]);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'data' => ['id', 'name'],
        ])
        ->assertJsonPath('data.name', 'John Doe');
});

it('returns validation error when name is missing', function () {
    Auth::guard('api')->login(User::factory()->create());

    $response = $this->postJson('/api/clients', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['name']);
});

it('returns validation error when name exceeds max length', function () {
    Auth::guard('api')->login(User::factory()->create());

    $response = $this->postJson('/api/clients', [
        'name' => str_repeat('a', 256),
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['name']);
});

it('creates multiple clients successfully', function () {
    Auth::guard('api')->login(User::factory()->create());

    $this->postJson('/api/clients', ['name' => 'Client 1']);
    $this->postJson('/api/clients', ['name' => 'Client 2']);

    $response = $this->getJson('/api/clients');

    $response->assertStatus(200)
        ->assertJsonCount(2, 'data');
});

it('returns 401 when creating a client without authentication', function () {
    $response = $this->postJson('/api/clients', ['name' => 'John Doe']);
    $response->assertStatus(401);
});

<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a client', function () {
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
    $response = $this->postJson('/api/clients', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['name']);
});

it('returns validation error when name exceeds max length', function () {
    $response = $this->postJson('/api/clients', [
        'name' => str_repeat('a', 256),
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['name']);
});

it('creates multiple clients successfully', function () {
    $this->postJson('/api/clients', ['name' => 'Client 1']);
    $this->postJson('/api/clients', ['name' => 'Client 2']);

    $response = $this->getJson('/api/clients');

    $response->assertStatus(200)
        ->assertJsonCount(2, 'data');
});

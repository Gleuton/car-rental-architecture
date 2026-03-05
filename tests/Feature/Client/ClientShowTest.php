<?php

declare(strict_types=1);

use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can show a client by id', function () {
    $client = Client::factory()->create(['name' => 'John Doe']);

    $response = $this->getJson("/api/clients/{$client->id}");

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => ['id', 'name'],
        ])
        ->assertJsonPath('data.id', $client->id)
        ->assertJsonPath('data.name', 'John Doe');
});

it('returns 404 when client is not found', function () {
    $response = $this->getJson('/api/clients/999999');

    $response->assertStatus(404);
});

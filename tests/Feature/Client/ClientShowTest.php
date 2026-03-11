<?php

declare(strict_types=1);

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

uses(RefreshDatabase::class);

it('can show a client by id', function () {
    Auth::guard('api')->login(User::factory()->create());

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
    Auth::guard('api')->login(User::factory()->create());

    $response = $this->getJson('/api/clients/999999');

    $response->assertStatus(404);
});

it('returns 401 when showing a client without authentication', function () {
    $client = Client::factory()->create();
    $response = $this->getJson("/api/clients/{$client->id}");
    $response->assertStatus(401);
});

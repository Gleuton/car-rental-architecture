<?php

declare(strict_types=1);

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

it('can show a client by uuid', function () {
    Auth::guard('api')->login(User::factory()->create());

    $client = Client::factory()->create(['name' => 'John Doe']);

    $response = $this->getJson("/api/clients/{$client->uuid}");

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => ['uuid', 'name'],
        ])
        ->assertJsonPath('data.uuid', $client->uuid)
        ->assertJsonPath('data.name', 'John Doe')
        ->assertJsonMissingPath('data.id');
});

it('returns 404 when client is not found', function () {
    Auth::guard('api')->login(User::factory()->create());

    $response = $this->getJson('/api/clients/'.(string) Str::uuid());

    $response->assertStatus(404);
});

it('returns 401 when showing a client without authentication', function () {
    $client = Client::factory()->create();
    $response = $this->getJson("/api/clients/{$client->uuid}");
    $response->assertStatus(401);
});

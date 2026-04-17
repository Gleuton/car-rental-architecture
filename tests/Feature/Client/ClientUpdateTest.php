<?php

declare(strict_types=1);

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

uses(RefreshDatabase::class);

it('can update client name', function () {
    Auth::guard('api')->login(User::factory()->create());

    $client = Client::factory()->create(['name' => 'John Doe']);

    $response = $this->putJson('/api/clients/'.$client->uuid, [
        'name' => 'John Updated',
    ]);

    $response->assertStatus(200)
        ->assertJsonPath('data.uuid', $client->uuid)
        ->assertJsonPath('data.name', 'John Updated')
        ->assertJsonMissingPath('data.id');

    $this->assertDatabaseHas('clients', [
        'uuid' => $client->uuid,
        'name' => 'John Updated',
    ]);
});

it('returns 404 when updating non-existent client', function () {
    Auth::guard('api')->login(User::factory()->create());

    $response = $this->putJson('/api/clients/not-found-uuid', [
        'name' => 'John Updated',
    ]);

    $response->assertStatus(404);
});

it('returns validation error when name exceeds max length on update', function () {
    Auth::guard('api')->login(User::factory()->create());

    $client = Client::factory()->create(['name' => 'John Doe']);

    $response = $this->putJson('/api/clients/'.$client->uuid, [
        'name' => str_repeat('a', 256),
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['name']);
});

it('returns 401 when updating a client without authentication', function () {
    $client = Client::factory()->create();
    $response = $this->putJson('/api/clients/'.$client->uuid, ['name' => 'Updated']);
    $response->assertStatus(401);
});

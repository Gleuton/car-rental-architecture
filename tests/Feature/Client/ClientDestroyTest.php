<?php

declare(strict_types=1);

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

uses(RefreshDatabase::class);

it('can delete a client', function () {
    Auth::guard('api')->login(User::factory()->create());

    $client = Client::factory()->create(['name' => 'John Doe']);

    $response = $this->deleteJson("/api/clients/{$client->uuid}");

    $response->assertStatus(204);

    $this->assertDatabaseMissing('clients', ['uuid' => $client->uuid]);
});

it('returns 404 when trying to delete non-existent client', function () {
    Auth::guard('api')->login(User::factory()->create());

    $response = $this->deleteJson('/api/clients/not-found-uuid');

    $response->assertStatus(404);
});

it('deletes client and can list remaining clients', function () {
    Auth::guard('api')->login(User::factory()->create());

    $client1 = Client::factory()->create(['name' => 'Client 1']);
    $client2 = Client::factory()->create(['name' => 'Client 2']);
    $client3 = Client::factory()->create(['name' => 'Client 3']);

    $this->deleteJson("/api/clients/{$client2->uuid}");

    $response = $this->getJson('/api/clients');

    $response->assertStatus(200)
        ->assertJsonCount(2, 'data');

    $this->assertDatabaseHas('clients', ['uuid' => $client1->uuid]);
    $this->assertDatabaseMissing('clients', ['uuid' => $client2->uuid]);
    $this->assertDatabaseHas('clients', ['uuid' => $client3->uuid]);
});

it('returns 401 when deleting a client without authentication', function () {
    $client = Client::factory()->create();
    $response = $this->deleteJson("/api/clients/{$client->uuid}");
    $response->assertStatus(401);
});

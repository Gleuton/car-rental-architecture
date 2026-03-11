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

    $response = $this->deleteJson("/api/clients/{$client->id}");

    $response->assertStatus(204);

    $this->assertDatabaseMissing('clients', ['id' => $client->id]);
});

it('returns 404 when trying to delete non-existent client', function () {
    Auth::guard('api')->login(User::factory()->create());

    $response = $this->deleteJson('/api/clients/999999');

    $response->assertStatus(404);
});

it('deletes client and can list remaining clients', function () {
    Auth::guard('api')->login(User::factory()->create());

    $client1 = Client::factory()->create(['name' => 'Client 1']);
    $client2 = Client::factory()->create(['name' => 'Client 2']);
    $client3 = Client::factory()->create(['name' => 'Client 3']);

    $this->deleteJson("/api/clients/{$client2->id}");

    $response = $this->getJson('/api/clients');

    $response->assertStatus(200)
        ->assertJsonCount(2, 'data');

    $this->assertDatabaseHas('clients', ['id' => $client1->id]);
    $this->assertDatabaseMissing('clients', ['id' => $client2->id]);
    $this->assertDatabaseHas('clients', ['id' => $client3->id]);
});

it('returns 401 when deleting a client without authentication', function () {
    $client = Client::factory()->create();
    $response = $this->deleteJson("/api/clients/{$client->id}");
    $response->assertStatus(401);
});

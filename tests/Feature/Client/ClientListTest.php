<?php

declare(strict_types=1);

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

uses(RefreshDatabase::class);

it('can list clients', function () {
    Auth::guard('api')->login(User::factory()->create());
    Client::factory()->count(3)->create();

    $response = $this->getJson('/api/clients');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'uuid', 'name'],
            ],
            'meta' => [
                'current_page',
                'per_page',
                'total',
                'last_page',
            ],
        ])
        ->assertJsonCount(3, 'data');

    expect($response->json('data.0.uuid'))->not->toBeNull();
});

it('can filter clients by name', function () {
    Auth::guard('api')->login(User::factory()->create());
    Client::factory()->create(['name' => 'John Doe']);
    Client::factory()->create(['name' => 'Jane Smith']);
    Client::factory()->create(['name' => 'John Silva']);

    $response = $this->getJson('/api/clients?search=john');

    $response->assertStatus(200)
        ->assertJsonCount(2, 'data');
});

it('can paginate clients', function () {
    Auth::guard('api')->login(User::factory()->create());
    Client::factory()->count(20)->create();

    $response = $this->getJson('/api/clients?per_page=10&page=1');

    $response->assertStatus(200)
        ->assertJsonCount(10, 'data')
        ->assertJsonPath('meta.current_page', 1)
        ->assertJsonPath('meta.per_page', 10)
        ->assertJsonPath('meta.total', 20);
});

it('can order clients by name ascending', function () {
    Auth::guard('api')->login(User::factory()->create());
    Client::factory()->create(['name' => 'Zoe']);
    Client::factory()->create(['name' => 'Alice']);
    Client::factory()->create(['name' => 'Bob']);

    $response = $this->getJson('/api/clients?order_by=name&direction=asc');

    $response->assertStatus(200);
    $data = $response->json('data');

    expect($data[0]['name'])->toBe('Alice')
        ->and($data[1]['name'])->toBe('Bob')
        ->and($data[2]['name'])->toBe('Zoe');
});

it('returns 401 when listing clients without authentication', function () {
    $response = $this->getJson('/api/clients');
    $response->assertStatus(401);
});

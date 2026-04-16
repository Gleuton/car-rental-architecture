<?php

declare(strict_types=1);

use App\Models\Brand;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

uses(RefreshDatabase::class);

it('can list brands', function () {
    Auth::guard('api')->login(User::factory()->create());
    Brand::factory()->count(3)->create();

    $response = $this->getJson('/api/brands');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'uuid', 'name', 'image'],
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

it('can filter brands by name', function () {
    Auth::guard('api')->login(User::factory()->create());
    Brand::factory()->create(['name' => 'Ferrari']);
    Brand::factory()->create(['name' => 'Fiat']);
    Brand::factory()->create(['name' => 'Ford']);

    $response = $this->getJson('/api/brands?search=fer');

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', 'Ferrari');
});

it('returns 401 when listing brands without authentication', function () {
    $response = $this->getJson('/api/brands');
    $response->assertStatus(401);
});

<?php

declare(strict_types=1);

use App\Models\Brand;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can list brands', function () {
    Brand::factory()->count(3)->create();

    $response = $this->getJson('/api/brands');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => ['name', 'image'],
            ],
            'meta' => [
                'current_page',
                'per_page',
                'total',
                'last_page',
            ],
        ])
        ->assertJsonCount(3, 'data');
});

it('can filter brands by name', function () {
    Brand::factory()->create(['name' => 'Ferrari']);
    Brand::factory()->create(['name' => 'Fiat']);
    Brand::factory()->create(['name' => 'Ford']);

    $response = $this->getJson('/api/brands?search=fer');

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', 'Ferrari');
});

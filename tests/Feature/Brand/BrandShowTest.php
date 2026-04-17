<?php

declare(strict_types=1);

use App\Models\Brand;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

uses(RefreshDatabase::class);

it('can show a brand', function () {
    Auth::guard('api')->login(User::factory()->create());
    /** @var Brand $brand */
    $brand = Brand::factory()->create();

    $response = $this->getJson("/api/brands/$brand->uuid");

    $response->assertStatus(200)
        ->assertJsonPath('data.uuid', $brand->uuid)
        ->assertJsonPath('data.name', $brand->name)
        ->assertJsonPath('data.image', $brand->image)
        ->assertJsonMissingPath('data.id');
});

it('returns 404 when showing non-existent brand', function () {
    Auth::guard('api')->login(User::factory()->create());

    $response = $this->getJson('/api/brands/invalid-uuid');

    $response->assertStatus(404);
});

it('returns 401 when showing a brand without authentication', function () {
    $brand = Brand::factory()->create();
    $response = $this->getJson("/api/brands/$brand->uuid");
    $response->assertStatus(401);
});

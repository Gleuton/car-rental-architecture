<?php

declare(strict_types=1);

use App\Models\Brand;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can show a brand', function () {
    /** @var Brand $brand */
    $brand = Brand::factory()->create();

    $response = $this->getJson("/api/brands/$brand->id");

    $response->assertStatus(200)
        ->assertJsonPath('data.name', $brand->name)
        ->assertJsonPath('data.image', $brand->image);
});

it('returns 404 when showing non-existent brand', function () {
    $response = $this->getJson('/api/brands/999');

    $response->assertStatus(404);
});

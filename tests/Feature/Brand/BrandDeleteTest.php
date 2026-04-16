<?php

declare(strict_types=1);

use App\Models\Brand;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
});

it('can delete brand', function () {
    Auth::guard('api')->login(User::factory()->create());

    /** @var Brand $factoryBrand */
    $factoryBrand = Brand::factory()->create(['name' => 'Toyota', 'image' => 'brands/toyota.png']);
    Storage::disk('public')->put('brands/toyota.png', 'fake content');

    $response = $this->deleteJson('/api/brands/'.$factoryBrand->uuid);

    $response->assertStatus(204);

    Storage::disk('public')->assertMissing('brands/toyota.png');

    $this->assertDatabaseMissing('brands', [
        'id' => $factoryBrand->id,
    ]);
});

it('returns 404 when deleting non-existent brand', function () {
    Auth::guard('api')->login(User::factory()->create());

    $response = $this->deleteJson('/api/brands/invalid-uuid');

    $response->assertStatus(404);
});

it('returns 401 when deleting a brand without authentication', function () {
    $brand = Brand::factory()->create(['name' => 'Toyota', 'image' => 'brands/toyota.png']);
    $response = $this->deleteJson('/api/brands/'.$brand->uuid);
    $response->assertStatus(401);
});

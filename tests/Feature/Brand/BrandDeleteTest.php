<?php

declare(strict_types=1);

use App\Models\Brand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
});

it('can delete brand', function () {
    /** @var Brand $factoryBrand */
    $factoryBrand = Brand::factory()->create(['name' => 'Toyota', 'image' => 'brands/toyota.png']);
    Storage::disk('public')->put('brands/toyota.png', 'fake content');

    $response = $this->deleteJson('/api/brands/'.$factoryBrand->id);

    $response->assertStatus(204);

    Storage::disk('public')->assertMissing('brands/toyota.png');

    $this->assertDatabaseMissing('brands', [
        'id' => $factoryBrand->id,
    ]);
});

it('returns 404 when deleting non-existent brand', function () {
    $response = $this->deleteJson('/api/brands/999');

    $response->assertStatus(404);
});

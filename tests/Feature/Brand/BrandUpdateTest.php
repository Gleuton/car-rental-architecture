<?php

declare(strict_types=1);

use App\Models\Brand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
});

it('can update all data in brand', function () {
    /** @var Brand $factoryBrand */
    $factoryBrand = Brand::factory()->create(['name' => 'Toyota_old', 'image' => 'brands/old.png']);
    Storage::disk('public')->put('brands/old.png', 'fake content');

    $file = UploadedFile::fake()->create('toyota_new.png', 100, 'image/png');

    $data = [
        'name' => 'Toyota',
        'image' => $file,
    ];

    $response = $this->putJson('/api/brands/'.$factoryBrand->id, $data);

    $response->assertStatus(200)
        ->assertJsonPath('data.name', 'Toyota');

    $brand = Brand::find($factoryBrand->id);
    expect($brand?->image)->not->toBe('brands/old.png');
    Storage::disk('public')->assertExists($brand?->image);
    Storage::disk('public')->assertMissing('brands/old.png');

    $this->assertDatabaseHas('brands', [
        'name' => 'Toyota',
    ]);
});

it('can update name only in brand', function () {
    /** @var Brand $factoryBrand */
    $factoryBrand = Brand::factory()->create(['name' => 'Toyota_old', 'image' => 'brands/old.png']);

    $data = [
        'name' => 'Toyota',
    ];

    $response = $this->putJson('/api/brands/'.$factoryBrand->id, $data);

    $response->assertStatus(200)
        ->assertJsonPath('data.name', 'Toyota')
        ->assertJsonPath('data.image', 'brands/old.png');

    $this->assertDatabaseHas('brands', [
        'name' => 'Toyota',
        'image' => 'brands/old.png',
    ]);
});

it('can update image only in brand', function () {
    /** @var Brand $factoryBrand */
    $factoryBrand = Brand::factory()->create(['name' => 'Toyota_old', 'image' => 'brands/old.png']);
    Storage::disk('public')->put('brands/old.png', 'fake content');

    $file = UploadedFile::fake()->create('toyota_new.png', 100, 'image/png');

    $data = [
        'image' => $file,
    ];

    $response = $this->putJson('/api/brands/'.$factoryBrand->id, $data);

    $response->assertStatus(200)
        ->assertJsonPath('data.name', 'Toyota_old');

    $brand = Brand::find($factoryBrand->id);
    expect($brand?->image)->not->toBe('brands/old.png');
    Storage::disk('public')->assertExists($brand?->image);
    Storage::disk('public')->assertMissing('brands/old.png');
});

it('cannot update a brand with duplicate name', function () {
    Brand::factory()->create(['name' => 'Toyota']);

    /** @var Brand $factoryBrand */
    $factoryBrand = Brand::factory()->create(['name' => 'Fiat']);

    $data = [
        'name' => 'Toyota',
    ];

    $response = $this->putJson('/api/brands/'.$factoryBrand->id, $data);

    $response->assertStatus(409)
        ->assertJson([
            'type' => 'DOMAIN_ERROR',
            'code' => 'ALREADY_EXISTS',
            'message' => 'Brand already exists',
        ]);
});

it('returns 404 when updating non-existent brand', function () {
    $data = [
        'name' => 'Toyota',
    ];

    $response = $this->putJson('/api/brands/999', $data);

    $response->assertStatus(404);
});

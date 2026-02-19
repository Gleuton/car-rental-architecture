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

it('can list brands', function () {
    Brand::factory()->count(3)->create();

    $response = $this->getJson('/api/brand');

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

    $response = $this->getJson('/api/brand?search=fer');

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', 'Ferrari');
});

it('can create a brand', function () {
    $file = UploadedFile::fake()->create('toyota.png', 100, 'image/png');

    $data = [
        'name' => 'Toyota',
        'image' => $file,
    ];

    $response = $this->postJson('/api/brand', $data);

    $response->assertStatus(201)
        ->assertJsonPath('data.name', 'Toyota');

    $brand = Brand::where('name', 'Toyota')->first();
    expect($brand->image)->not->toBeEmpty();
    Storage::disk('public')->assertExists($brand->image);

    $this->assertDatabaseHas('brands', [
        'name' => 'Toyota',
    ]);
});

it('cannot create a brand with duplicate name', function () {
    Brand::factory()->create(['name' => 'Toyota']);

    $file = UploadedFile::fake()->create('toyota_2.png', 100, 'image/png');

    $data = [
        'name' => 'Toyota',
        'image' => $file,
    ];

    $response = $this->postJson('/api/brand', $data);

    $response->assertStatus(409)
        ->assertJson([
            'type' => 'DOMAIN_ERROR',
            'code' => 'ALREADY_EXISTS',
            'message' => 'Brand already exists',
        ]);
});

it('can show a brand', function () {
    /** @var Brand $brand */
    $brand = Brand::factory()->create();

    $response = $this->getJson("/api/brand/$brand->id");

    $response->assertStatus(200)
        ->assertJsonPath('data.name', $brand->name)
        ->assertJsonPath('data.image', $brand->image);
});

it('returns 404 when showing non-existent brand', function () {
    $response = $this->getJson('/api/brand/999');

    $response->assertStatus(404);
});

it('validates brand creation', function () {
    $response = $this->postJson('/api/brand');

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'image']);
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

    $response = $this->putJson('/api/brand/'.$factoryBrand->id, $data);

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

    $response = $this->putJson('/api/brand/'.$factoryBrand->id, $data);

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

    $response = $this->putJson('/api/brand/'.$factoryBrand->id, $data);

    $response->assertStatus(200)
        ->assertJsonPath('data.name', 'Toyota_old');

    $brand = Brand::find($factoryBrand->id);
    expect($brand->image)->not->toBe('brands/old.png');
    Storage::disk('public')->assertExists($brand->image);
    Storage::disk('public')->assertMissing('brands/old.png');
});

it('can delete brand', function () {
    /** @var Brand $factoryBrand */
    $factoryBrand = Brand::factory()->create(['name' => 'Toyota', 'image' => 'brands/toyota.png']);
    Storage::disk('public')->put('brands/toyota.png', 'fake content');

    $response = $this->deleteJson('/api/brand/'.$factoryBrand->id);

    $response->assertStatus(200);

    Storage::disk('public')->assertMissing('brands/toyota.png');

    $this->assertDatabaseMissing('brands', [
        'id' => $factoryBrand->id,
    ]);
});

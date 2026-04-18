<?php

declare(strict_types=1);

use App\Models\Brand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
});

it('can create a brand', function () {
    $file = UploadedFile::fake()->create('toyota.png', 100, 'image/png');

    authenticateApi();

    $data = [
        'name' => 'Toyota',
        'image' => $file,
    ];

    $response = $this->postJson(
        '/api/brands',
        $data,
    );

    $response->assertStatus(201)
        ->assertJsonStructure([
            'data' => ['uuid', 'name', 'image'],
        ])
        ->assertJsonPath('data.uuid', static fn (string $uuid): bool => Str::isUuid($uuid))
        ->assertJsonPath('data.name', 'Toyota')
        ->assertJsonMissingPath('data.id');

    $brand = Brand::where('name', 'Toyota')->first();
    expect($brand->image)->not->toBeEmpty();
    expect($brand->uuid)->not->toBeNull()
        ->and(Str::isUuid($brand->uuid))->toBeTrue();
    Storage::disk('public')->assertExists($brand->image);

    $this->assertDatabaseHas('brands', [
        'name' => 'Toyota',
    ]);
});

it('returns 401 when creating a brand without authentication', function () {
    $file = UploadedFile::fake()->create('toyota.png', 100, 'image/png');

    $response = $this->postJson('/api/brands', [
        'name' => 'Toyota',
        'image' => $file,
    ]);

    $response->assertStatus(401);
});

it('validates brand creation', function () {
    authenticateApi();

    $response = $this->postJson('/api/brands');

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'image']);
});

it('cannot create a brand with duplicate name', function () {
    Brand::factory()->create(['name' => 'Toyota']);
    authenticateApi();

    $file = UploadedFile::fake()->create('toyota_2.png', 100, 'image/png');

    $data = [
        'name' => 'Toyota',
        'image' => $file,
    ];

    $response = $this->postJson('/api/brands', $data);

    $response->assertStatus(409)
        ->assertJson([
            'type' => 'DOMAIN_ERROR',
            'code' => 'ALREADY_EXISTS',
            'message' => 'Brand already exists',
        ]);
});

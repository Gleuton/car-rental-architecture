<?php

use App\Models\Brand;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can list brands', function () {
    Brand::factory()->count(3)->create();

    $response = $this->getJson('/api/brand');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => ['name', 'image'],
            ],
            'current_page',
            'last_page',
            'total',
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
    $data = [
        'name' => 'Toyota',
        'image' => 'toyota_logo.png',
    ];

    $response = $this->postJson('/api/brand', $data);

    $response->assertStatus(201)
        ->assertJsonPath('data.name', 'Toyota')
        ->assertJsonPath('data.image', 'toyota_logo.png');

    $this->assertDatabaseHas('brands', [
        'name' => 'Toyota',
    ]);
});

it('cannot create a brand with duplicate name', function () {
    Brand::factory()->create(['name' => 'Toyota']);

    $data = [
        'name' => 'Toyota',
        'image' => 'toyota_logo_2.png',
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
        ->assertJsonPath('name', $brand->name)
        ->assertJsonPath('image', $brand->image);
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
    $factoryBrand = Brand::factory()->create(['name' => 'Toyota_old', 'image' => 'toyota_logo_old.png']);

    $data = [
        'name' => 'Toyota',
        'image' => 'toyota_logo.png',
    ];

    $response = $this->putJson('/api/brand/' . $factoryBrand->id, $data);

    $response->assertStatus(200)
        ->assertJsonPath('data.name', 'Toyota')
        ->assertJsonPath('data.image', 'toyota_logo.png');

    $this->assertDatabaseHas('brands', [
        'name' => 'Toyota',
        'image' => 'toyota_logo.png',
    ]);
});

it('can update name only in brand', function () {
    /** @var Brand $factoryBrand */
    $factoryBrand = Brand::factory()->create(['name' => 'Toyota_old', 'image' => 'toyota_logo_old.png']);

    $data = [
        'name' => 'Toyota',
    ];

    $response = $this->putJson('/api/brand/' . $factoryBrand->id, $data);

    $response->assertStatus(200)
        ->assertJsonPath('data.name', 'Toyota')
        ->assertJsonPath('data.image', 'toyota_logo_old.png');

    $this->assertDatabaseHas('brands', [
        'name' => 'Toyota',
        'image' => 'toyota_logo_old.png',
    ]);
});

it('can update image only in brand', function () {
    /** @var Brand $factoryBrand */
    $factoryBrand = Brand::factory()->create(['name' => 'Toyota_old', 'image' => 'toyota_logo_old.png']);

    $data = [
        'image' => 'toyota.png',
    ];

    $response = $this->putJson('/api/brand/' . $factoryBrand->id, $data);

    $response->assertStatus(200)
        ->assertJsonPath('data.name', 'Toyota_old')
        ->assertJsonPath('data.image', 'toyota.png');

    $this->assertDatabaseHas('brands', [
        'name' => 'Toyota_old',
        'image' => 'toyota.png',
    ]);
});

it('can delete brand', function () {
    /** @var Brand $factoryBrand */
    $factoryBrand = Brand::factory()->create(['name' => 'Toyota', 'image' => 'toyota_logo.png']);

    $response = $this->deleteJson('/api/brand/' . $factoryBrand->id);

    $response->assertStatus(200);

    $this->assertDatabaseMissing('brands', [
        'name' => 'Toyota',
        'image' => 'toyota_logo.png',
    ]);
});
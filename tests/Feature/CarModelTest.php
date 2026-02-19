<?php

declare(strict_types=1);

use App\Models\Brand;
use App\Models\CarModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
});

it('can create a CarModel', function () {
    $brand = Brand::factory()->create();

    $file = UploadedFile::fake()->create('toyota_corolla.png', 100, 'image/png');

    $data = [
        'brand_id' => $brand->id,
        'name' => 'Corolla',
        'image' => $file,
        'doors_number' => 4,
        'seats_number' => 5,
        'airbags' => true,
        'abs' => true,
    ];

    $response = $this->postJson('/api/car-model', $data);

    $response->assertStatus(200);

    $carModel = CarModel::where('name', 'Corolla')->first();
    expect($carModel)->not->toBeNull()
        ->and($carModel->brand_id)->toBe($brand->id)
        ->and($carModel->image)->not->toBeEmpty();

    Storage::disk('public')->assertExists($carModel->image);

    $this->assertDatabaseHas('car_models', [
        'brand_id' => $brand->id,
        'name' => 'Corolla',
        'doors' => 4,
        'seats' => 5,
        'airbags' => true,
        'abs' => true,
    ]);
});

it('validates required fields when creating a CarModel', function () {
    $response = $this->postJson('/api/car-model', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'brand_id',
            'name',
            'image',
            'doors_number',
            'seats_number',
            'airbags',
            'abs',
        ]);
});

it('validates doors_number range when creating a CarModel', function () {
    $brand = Brand::factory()->create();
    $file = UploadedFile::fake()->create('toyota_corolla.png', 100, 'image/png');

    $data = [
        'brand_id' => $brand->id,
        'name' => 'Corolla',
        'image' => $file,
        'doors_number' => 1,
        'seats_number' => 5,
        'airbags' => true,
        'abs' => true,
    ];

    $response = $this->postJson('/api/car-model', $data);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['doors_number']);
});

it('validates seats_number range when creating a CarModel', function () {
    $brand = Brand::factory()->create();
    $file = UploadedFile::fake()->create('toyota_corolla.png', 100, 'image/png');

    $data = [
        'brand_id' => $brand->id,
        'name' => 'Corolla',
        'image' => $file,
        'doors_number' => 4,
        'seats_number' => 8,
        'airbags' => true,
        'abs' => true,
    ];

    $response = $this->postJson('/api/car-model', $data);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['seats_number']);
});

it('validates image type when creating a CarModel', function () {
    $brand = Brand::factory()->create();
    $file = UploadedFile::fake()->create('manual.pdf', 100, 'application/pdf');

    $data = [
        'brand_id' => $brand->id,
        'name' => 'Corolla',
        'image' => $file,
        'doors_number' => 4,
        'seats_number' => 5,
        'airbags' => true,
        'abs' => true,
    ];

    $response = $this->postJson('/api/car-model', $data);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['image']);
});

it('returns domain error when brand does not exist', function () {
    $file = UploadedFile::fake()->create('toyota_corolla.png', 100, 'image/png');

    $data = [
        'brand_id' => 999,
        'name' => 'Corolla',
        'image' => $file,
        'doors_number' => 4,
        'seats_number' => 5,
        'airbags' => true,
        'abs' => true,
    ];

    $response = $this->postJson('/api/car-model', $data);

    $response->assertStatus(409)
        ->assertJson([
            'type' => 'DOMAIN_ERROR',
            'domain' => 'brand',
            'code' => 'NOT_FOUND',
            'message' => 'Brand not found',
        ]);
});

it('returns domain error when car model already exists for the brand', function () {
    $brand = Brand::factory()->create();

    CarModel::create([
        'brand_id' => $brand->id,
        'name' => 'Corolla',
        'image' => 'car_models/corolla.png',
        'doors' => 4,
        'seats' => 5,
        'airbags' => true,
        'abs' => true,
    ]);

    $file = UploadedFile::fake()->create('toyota_corolla.png', 100, 'image/png');

    $data = [
        'brand_id' => $brand->id,
        'name' => 'Corolla',
        'image' => $file,
        'doors_number' => 4,
        'seats_number' => 5,
        'airbags' => true,
        'abs' => true,
    ];

    $response = $this->postJson('/api/car-model', $data);

    $response->assertStatus(409)
        ->assertJson([
            'type' => 'DOMAIN_ERROR',
            'domain' => 'car_model',
            'code' => 'ALREADY_EXISTS',
            'message' => 'Car model already exists for this brand',
        ]);
});

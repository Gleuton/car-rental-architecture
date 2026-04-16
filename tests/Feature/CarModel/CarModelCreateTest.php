<?php

declare(strict_types=1);

use App\Models\Brand;
use App\Models\CarModel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
});

it('can create a CarModel', function () {
    Auth::guard('api')->login(User::factory()->create());
    /** @var Brand $brand */
    $brand = Brand::factory()->create();
    $file = UploadedFile::fake()->create('toyota_corolla.png', 100, 'image/png');
    $carModelName = 'Corolla';
    $data = [
        'brand_id' => $brand->id,
        'name' => $carModelName,
        'image' => $file,
        'doors_number' => 4,
        'seats_number' => 5,
        'airbags' => true,
        'abs' => true,
    ];

    $response = $this->postJson('/api/car-models', $data);
    $response->assertStatus(200)
        ->assertJsonPath('data.id', fn ($id) => is_int($id))
        ->assertJsonPath('data.brandId', $brand->id)
        ->assertJsonPath('data.name', $carModelName)
        ->assertJsonPath('data.image', 'car_models/toyota_corolla.png')
        ->assertJsonPath('data.doorsNumber', 4)
        ->assertJsonPath('data.seatsNumber', 5)
        ->assertJsonPath('data.airbags', true)
        ->assertJsonPath('data.abs', true);

    $carModel = CarModel::where('name', $carModelName)->first();

    expect($carModel)->not->toBeNull()->and($carModel->brand_id)->toBe($brand->id)->and(
        $carModel->image
    )->not->toBeEmpty()
        ->and($carModel->uuid)->not->toBeNull()
        ->and(Str::isUuid($carModel->uuid))->toBeTrue();

    Storage::disk('public')->assertExists($carModel->image);
    $this->assertDatabaseHas(
        'car_models',
        [
            'brand_id' => $brand->id,
            'name' => $carModelName,
            'doors' => 4,
            'seats' => 5,
            'airbags' => true,
            'abs' => true,
        ]
    );
});

it('validates required fields when creating a CarModel', function () {
    Auth::guard('api')->login(User::factory()->create());
    $response = $this->postJson('/api/car-models');

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
    Auth::guard('api')->login(User::factory()->create());
    /** @var Brand $brand */
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

    $response = $this->postJson('/api/car-models', $data);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['doors_number']);
});

it('validates seats_number range when creating a CarModel', function () {
    Auth::guard('api')->login(User::factory()->create());
    /** @var Brand $brand */
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

    $response = $this->postJson('/api/car-models', $data);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['seats_number']);
});

it('validates image type when creating a CarModel', function () {
    Auth::guard('api')->login(User::factory()->create());
    /** @var Brand $brand */
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

    $response = $this->postJson('/api/car-models', $data);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['image']);
});

it('returns domain error when brand does not exist', function () {
    Auth::guard('api')->login(User::factory()->create());
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

    $response = $this->postJson('/api/car-models', $data);

    $response->assertStatus(409)
        ->assertJson([
            'type' => 'DOMAIN_ERROR',
            'domain' => 'brand',
            'code' => 'NOT_FOUND',
            'message' => 'Brand not found',
        ]);
});

it('returns domain error when car model already exists for the brand', function () {
    Auth::guard('api')->login(User::factory()->create());
    /** @var Brand $brand */
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

    $response = $this->postJson('/api/car-models', $data);

    $response->assertStatus(409)
        ->assertJson([
            'type' => 'DOMAIN_ERROR',
            'domain' => 'car_model',
            'code' => 'ALREADY_EXISTS',
            'message' => 'Car model already exists for this brand',
        ]);
});

it('returns 401 when creating a car model without authentication', function () {
    $brand = Brand::factory()->create();
    $file = UploadedFile::fake()->create('corolla.png', 100, 'image/png');
    $response = $this->postJson('/api/car-models', [
        'brand_id' => $brand->id,
        'name' => 'Corolla',
        'image' => $file,
        'doors_number' => 4,
        'seats_number' => 5,
        'airbags' => true,
        'abs' => true,
    ]);
    $response->assertStatus(401);
});

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

    $response = $this->postJson('/api/car-model', $data);
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
    )->not->toBeEmpty();

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
    $response = $this->postJson('/api/car-model');

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

    $response = $this->postJson('/api/car-model', $data);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['doors_number']);
});

it('validates seats_number range when creating a CarModel', function () {
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

    $response = $this->postJson('/api/car-model', $data);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['seats_number']);
});

it('validates image type when creating a CarModel', function () {
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

    $response = $this->postJson('/api/car-model', $data);

    $response->assertStatus(409)
        ->assertJson([
            'type' => 'DOMAIN_ERROR',
            'domain' => 'car_model',
            'code' => 'ALREADY_EXISTS',
            'message' => 'Car model already exists for this brand',
        ]);
});

it('can update name and brand in ModelCar', function () {
    /** @var Brand $newBrand */
    $newBrand = Brand::factory()->create();

    /** @var CarModel $factoryModelCar */
    $factoryModelCar = CarModel::factory()->create(['name' => 'Yaris']);

    $newModelName = 'Corolla';
    $data = [
        'name' => $newModelName,
        'brand_id' => $newBrand->id,
    ];

    $response = $this->putJson('/api/car-model/'.$factoryModelCar->id, $data);

    $response->assertStatus(200)
        ->assertJsonPath('data.name', $newModelName)
        ->assertJsonPath('data.brandId', $newBrand->id);

    $this->assertDatabaseHas('car_models', [
        'id' => $factoryModelCar->id,
        'name' => $newModelName,
        'brand_id' => $newBrand->id,
        'doors' => $factoryModelCar->doors,
        'seats' => $factoryModelCar->seats,
        'airbags' => (int) $factoryModelCar->airbags,
        'abs' => (int) $factoryModelCar->abs,
    ]);
});

it('cant update name if other model has the same name in ModelCar', function () {
    /** @var CarModel $modelCar */
    $modelCar = CarModel::factory()->create(['name' => 'Yaris']);

    /** @var CarModel $otherModelCar */
    CarModel::factory()->create([
        'name' => 'Corolla',
        'brand_id' => $modelCar->brand_id,
    ]);

    $newModelName = 'Corolla';
    $data = [
        'name' => $newModelName,
    ];

    $response = $this->putJson('/api/car-model/'.$modelCar->id, $data);

    $response->assertStatus(409)
        ->assertJson([
            'type' => 'DOMAIN_ERROR',
            'domain' => 'car_model',
            'code' => 'ALREADY_EXISTS',
            'message' => 'Car model already exists for this brand',
        ]);
});

it('can update all data in ModelCar', function () {
    /** @var Brand $newBrand */
    $newBrand = Brand::factory()->create();

    /** @var Brand $oldBrand */
    $oldBrand = Brand::factory()->create();

    /** @var CarModel $factoryModelCar */
    $factoryModelCar = CarModel::factory()->create([
        'name' => 'Corolla',
        'doors' => 5,
        'seats' => 6,
        'airbags' => false,
        'abs' => false,
        'image' => 'car_models/corolla.png',
        'brand_id' => $oldBrand->id,
    ]);

    $file = UploadedFile::fake()->create('yaris.png', 100, 'image/png');

    $carModelDetails = [
        'name' => 'Yaris',
        'brand_id' => $newBrand->id,
        'doors_number' => 4,
        'seats_number' => 5,
        'airbags' => true,
        'abs' => true,
        'image' => $file,
    ];

    $response = $this->putJson('/api/car-model/'.$factoryModelCar->id, $carModelDetails);

    $response->assertStatus(200)
        ->assertJsonPath('data.name', $carModelDetails['name'])
        ->assertJsonPath('data.brandId', $carModelDetails['brand_id']);

    $this->assertDatabaseHas('car_models', [
        'id' => $factoryModelCar->id,
        'name' => $carModelDetails['name'],
        'brand_id' => $carModelDetails['brand_id'],
        'doors' => $carModelDetails['doors_number'],
        'seats' => $carModelDetails['seats_number'],
        'airbags' => (int) $carModelDetails['airbags'],
        'abs' => (int) $carModelDetails['abs'],
    ]);
});

it('can send same name to update name in ModelCar', function () {
    /** @var CarModel $factoryModelCar */
    $factoryModelCar = CarModel::factory()->create(['name' => 'Corolla']);

    $data = [
        'name' => 'Corolla',
    ];

    $response = $this->putJson('/api/car-model/'.$factoryModelCar->id, $data);

    $response->assertStatus(200)
        ->assertJsonPath('data.name', 'Corolla');

    $this->assertDatabaseHas('car_models', [
        'id' => $factoryModelCar->id,
        'name' => 'Corolla',
        'brand_id' => $factoryModelCar->brand_id,
        'doors' => $factoryModelCar->doors,
        'seats' => $factoryModelCar->seats,
        'airbags' => (int) $factoryModelCar->airbags,
        'abs' => (int) $factoryModelCar->abs,
    ]);
});

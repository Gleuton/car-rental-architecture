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

it('can update name and brand in model car', function () {
    /** @var Brand $newBrand */
    $newBrand = Brand::factory()->create();

    /** @var CarModel $factoryModelCar */
    $factoryModelCar = CarModel::factory()->create(['name' => 'Yaris']);

    $newModelName = 'Corolla';
    $data = [
        'name' => $newModelName,
        'brand_id' => $newBrand->id,
    ];

    $response = $this->putJson('/api/car-models/'.$factoryModelCar->id, $data);

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

it('cant update name if other model has the same name in model car', function () {
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

    $response = $this->putJson('/api/car-models/'.$modelCar->id, $data);

    $response->assertStatus(409)
        ->assertJson([
            'type' => 'DOMAIN_ERROR',
            'domain' => 'car_model',
            'code' => 'ALREADY_EXISTS',
            'message' => 'Car model already exists for this brand',
        ]);
});

it('cant update model car with a invalid brand', function () {
    /** @var CarModel $modelCar */
    $modelCar = CarModel::factory()->create(['name' => 'Yaris']);

    /** @var CarModel $otherModelCar */
    CarModel::factory()->create([
        'name' => 'Corolla',
        'brand_id' => $modelCar->brand_id,
    ]);

    $data = [
        'brand_id' => 999,
    ];

    $response = $this->putJson('/api/car-models/'.$modelCar->id, $data);

    $response->assertStatus(409)
        ->assertJson([
            'type' => 'DOMAIN_ERROR',
            'domain' => 'brand',
            'code' => 'NOT_FOUND',
            'message' => 'Brand not found',
        ]);
});

it('can update all data in model car', function () {
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

    $response = $this->putJson('/api/car-models/'.$factoryModelCar->id, $carModelDetails);

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

it('can send same name to update name in model car', function () {
    /** @var CarModel $factoryModelCar */
    $factoryModelCar = CarModel::factory()->create(['name' => 'Corolla']);

    $data = [
        'name' => 'Corolla',
    ];

    $response = $this->putJson('/api/car-models/'.$factoryModelCar->id, $data);

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

it('can update image only in model car', function () {
    /** @var CarModel $factoryModelCar */
    $factoryModelCar = CarModel::factory()->create([
        'name' => 'Corolla',
        'image' => 'car_models/corolla.png',
    ]);
    Storage::disk('public')->put('car_models/corolla.png', 'fake content');

    $file = UploadedFile::fake()->create('corolla_new.png', 100, 'image/png');

    $data = [
        'image' => $file,
    ];

    $response = $this->putJson('/api/car-models/'.$factoryModelCar->id, $data);

    $response->assertStatus(200)
        ->assertJsonPath('data.name', 'Corolla');

    $carModel = CarModel::find($factoryModelCar->id);
    expect($carModel?->image)->not->toBe('car_models/corolla.png');
    Storage::disk('public')->assertExists($carModel?->image);
    Storage::disk('public')->assertMissing('car_models/corolla.png');
});

it('returns 404 when updating non-existent model car', function () {
    $data = [
        'name' => 'Corolla',
    ];

    $response = $this->putJson('/api/car-models/999', $data);

    $response->assertStatus(404);
});

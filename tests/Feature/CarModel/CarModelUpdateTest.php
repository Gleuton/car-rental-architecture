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

it('can update name and brand in model car', function () {
    Auth::guard('api')->login(User::factory()->create());

    /** @var Brand $newBrand */
    $newBrand = Brand::factory()->create();

    /** @var CarModel $factoryModelCar */
    $factoryModelCar = CarModel::factory()->create(['name' => 'Yaris']);

    $newModelName = 'Corolla';
    $data = [
        'name' => $newModelName,
        'brand_uuid' => $newBrand->uuid,
    ];

    $response = $this->putJson('/api/car-models/'.$factoryModelCar->uuid, $data);

    $response->assertStatus(200)
        ->assertJsonPath('data.uuid', $factoryModelCar->uuid)
        ->assertJsonPath('data.name', $newModelName)
        ->assertJsonPath('data.brandName', $newBrand->name)
        ->assertJsonMissingPath('data.id');

    $this->assertDatabaseHas('car_models', [
        'uuid' => $factoryModelCar->uuid,
        'name' => $newModelName,
        'brand_uuid' => $newBrand->uuid,
        'doors' => $factoryModelCar->doors,
        'seats' => $factoryModelCar->seats,
        'airbags' => (int) $factoryModelCar->airbags,
        'abs' => (int) $factoryModelCar->abs,
    ]);
});

it('cant update name if other model has the same name in model car', function () {
    Auth::guard('api')->login(User::factory()->create());

    /** @var CarModel $modelCar */
    $modelCar = CarModel::factory()->create(['name' => 'Yaris']);

    CarModel::factory()->create([
        'uuid' => (string) Str::uuid(),
        'name' => 'Corolla',
        'brand_uuid' => $modelCar->brand_uuid,
        'image' => 'car_models/corolla.png',
        'doors' => 4,
        'seats' => 5,
        'airbags' => true,
        'abs' => true,
    ]);

    $newModelName = 'Corolla';
    $data = [
        'name' => $newModelName,
    ];

    $response = $this->putJson('/api/car-models/'.$modelCar->uuid, $data);

    $response->assertStatus(409)
        ->assertJson([
            'type' => 'DOMAIN_ERROR',
            'domain' => 'car_model',
            'code' => 'ALREADY_EXISTS',
            'message' => 'Car model already exists for this brand',
        ]);
});

it('cant update model car with a invalid brand', function () {
    Auth::guard('api')->login(User::factory()->create());

    /** @var CarModel $modelCar */
    $modelCar = CarModel::factory()->create(['name' => 'Yaris']);

    /** @var CarModel $otherModelCar */
    CarModel::factory()->create([
        'name' => 'Corolla',
        'brand_uuid' => $modelCar->brand_uuid,
    ]);

    $data = [
        'brand_uuid' => '99999999-9999-4999-8999-999999999999',
    ];

    $response = $this->putJson('/api/car-models/'.$modelCar->uuid, $data);

    $response->assertStatus(409)
        ->assertJson([
            'type' => 'DOMAIN_ERROR',
            'domain' => 'brand',
            'code' => 'NOT_FOUND',
            'message' => 'Brand not found',
        ]);
});

it('can update all data in model car', function () {
    Auth::guard('api')->login(User::factory()->create());

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
        'brand_uuid' => $oldBrand->uuid,
    ]);

    $file = UploadedFile::fake()->create('yaris.png', 100, 'image/png');

    $carModelDetails = [
        'name' => 'Yaris',
        'brand_uuid' => $newBrand->uuid,
        'doors_number' => 4,
        'seats_number' => 5,
        'airbags' => true,
        'abs' => true,
        'image' => $file,
    ];

    $response = $this->putJson('/api/car-models/'.$factoryModelCar->uuid, $carModelDetails);

    $response->assertStatus(200)
        ->assertJsonPath('data.uuid', $factoryModelCar->uuid)
        ->assertJsonPath('data.name', $carModelDetails['name'])
        ->assertJsonPath('data.brandName', $newBrand->name)
        ->assertJsonMissingPath('data.id');

    $this->assertDatabaseHas('car_models', [
        'uuid' => $factoryModelCar->uuid,
        'name' => $carModelDetails['name'],
        'brand_uuid' => $newBrand->uuid,
        'doors' => $carModelDetails['doors_number'],
        'seats' => $carModelDetails['seats_number'],
        'airbags' => (int) $carModelDetails['airbags'],
        'abs' => (int) $carModelDetails['abs'],
    ]);
});

it('can send same name to update name in model car', function () {
    Auth::guard('api')->login(User::factory()->create());

    /** @var CarModel $factoryModelCar */
    $factoryModelCar = CarModel::factory()->create(['name' => 'Corolla']);

    $data = [
        'name' => 'Corolla',
    ];

    $response = $this->putJson('/api/car-models/'.$factoryModelCar->uuid, $data);

    $response->assertStatus(200)
        ->assertJsonPath('data.uuid', $factoryModelCar->uuid)
        ->assertJsonPath('data.name', 'Corolla')
        ->assertJsonMissingPath('data.id');

    $this->assertDatabaseHas('car_models', [
        'uuid' => $factoryModelCar->uuid,
        'name' => 'Corolla',
        'brand_uuid' => $factoryModelCar->brand->uuid,
        'doors' => $factoryModelCar->doors,
        'seats' => $factoryModelCar->seats,
        'airbags' => (int) $factoryModelCar->airbags,
        'abs' => (int) $factoryModelCar->abs,
    ]);
});

it('can update image only in model car', function () {
    Auth::guard('api')->login(User::factory()->create());

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

    $response = $this->putJson('/api/car-models/'.$factoryModelCar->uuid, $data);

    $response->assertStatus(200)
        ->assertJsonPath('data.uuid', $factoryModelCar->uuid)
        ->assertJsonPath('data.name', 'Corolla')
        ->assertJsonMissingPath('data.id');

    $carModel = CarModel::query()->where('uuid', $factoryModelCar->uuid)->first();
    expect($carModel?->image)->not->toBe('car_models/corolla.png');
    Storage::disk('public')->assertExists($carModel?->image);
    Storage::disk('public')->assertMissing('car_models/corolla.png');
});

it('returns 404 when updating non-existent model car', function () {
    Auth::guard('api')->login(User::factory()->create());

    $data = [
        'name' => 'Corolla',
    ];

    $response = $this->putJson('/api/car-models/999', $data);

    $response->assertStatus(404);
});

it('returns 401 when updating a car model without authentication', function () {
    $carModel = CarModel::factory()->create();
    $response = $this->putJson('/api/car-models/'.$carModel->uuid, ['name' => 'Updated']);
    $response->assertStatus(401);
});

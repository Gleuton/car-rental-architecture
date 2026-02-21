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

it('can list car models', function () {
    CarModel::factory()->count(20)->create();

    $response = $this->getJson('/api/car-model');

    $response->assertSuccessful()
        ->assertJsonCount(15, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'brandId',
                    'name',
                    'image',
                    'doorsNumber',
                    'seatsNumber',
                    'airbags',
                    'abs',
                ],
            ],
            'meta' => [
                'current_page',
                'per_page',
                'total',
                'last_page',
            ],
        ]);
});

it('can search car models by name', function () {
    CarModel::factory()->create(['name' => 'Corolla']);
    CarModel::factory()->create(['name' => 'Civic']);

    $response = $this->getJson('/api/car-model?search=corolla');

    $response->assertSuccessful()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', 'Corolla');
});

it('can paginate car models', function () {
    CarModel::factory()->count(20)->create();

    $response = $this->getJson('/api/car-model?per_page=5&page=2');

    $response->assertSuccessful()
        ->assertJsonCount(5, 'data')
        ->assertJsonPath('meta.current_page', 2)
        ->assertJsonPath('meta.per_page', 5);
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

    $response = $this->putJson('/api/car-model/'.$modelCar->id, $data);

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

    $response = $this->putJson('/api/car-model/'.$modelCar->id, $data);

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

it('can send same name to update name in model car', function () {
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

    $response = $this->putJson('/api/car-model/'.$factoryModelCar->id, $data);

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

    $response = $this->putJson('/api/car-model/999', $data);

    $response->assertStatus(404);
});

it('can show a model car', function () {
    /** @var CarModel $carModel */
    $carModel = CarModel::factory()->create();

    $response = $this->getJson("/api/car-model/$carModel->id");

    $response->assertStatus(200)
        ->assertJsonPath('data.id', fn ($id) => is_int($id))
        ->assertJsonPath('data.brandId', $carModel->brand_id)
        ->assertJsonPath('data.name', $carModel->name)
        ->assertJsonPath('data.image', $carModel->image)
        ->assertJsonPath('data.doorsNumber', $carModel->doors)
        ->assertJsonPath('data.seatsNumber', $carModel->seats)
        ->assertJsonPath('data.airbags', $carModel->airbags)
        ->assertJsonPath('data.abs', $carModel->abs);
});

it('returns 404 when tray show non-existent ModelCar', function () {
    $response = $this->getJson('/api/car-model/999');

    $response->assertStatus(404);
});

it('can dele a model car', function () {
    $imagePath = 'model-car/toyota.png';
    /** @var CarModel $carModel */
    $carModel = CarModel::factory()->create([
        'image' => $imagePath,
    ]);

    Storage::disk('public')->put($imagePath, 'fake content');

    $response = $this->deleteJson('/api/car-model/'.$carModel->id);

    Storage::disk('public')->assertMissing($imagePath);

    $response->assertStatus(204);

    $this->assertDatabaseMissing('car_models', [
        'id' => $carModel->id,
    ]);
});

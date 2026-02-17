<?php

use App\Models\Brand;
use App\Models\CarModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('can create a CarModel', function () {
    Storage::fake('public');
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

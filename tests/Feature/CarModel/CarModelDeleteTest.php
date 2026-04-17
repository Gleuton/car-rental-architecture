<?php

declare(strict_types=1);

use App\Models\CarModel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
});

it('can dele a model car', function () {
    Auth::guard('api')->login(User::factory()->create());
    $imagePath = 'model-car/toyota.png';
    /** @var CarModel $carModel */
    $carModel = CarModel::factory()->create([
        'image' => $imagePath,
    ]);

    Storage::disk('public')->put($imagePath, 'fake content');

    $response = $this->deleteJson('/api/car-models/'.$carModel->uuid);

    Storage::disk('public')->assertMissing($imagePath);

    $response->assertStatus(204);

    $this->assertDatabaseMissing('car_models', [
        'uuid' => $carModel->uuid,
    ]);
});

it('returns 401 when deleting a car model without authentication', function () {
    $carModel = CarModel::factory()->create();
    $response = $this->deleteJson('/api/car-models/'.$carModel->uuid);
    $response->assertStatus(401);
});

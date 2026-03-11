<?php

declare(strict_types=1);

use App\Models\Car;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

uses(RefreshDatabase::class);

it('can show a car', function () {
    Auth::guard('api')->login(User::factory()->create());

    /** @var Car $car */
    $car = Car::factory()->create([
        'license_plate' => 'ABC-1234',
        'color' => 'red',
        'is_available' => true,
        'km' => 1000,
    ]);

    $response = $this->getJson('/api/cars/'.$car->id);
    $response->assertStatus(200)
        ->assertJsonPath('data.licensePlate', 'ABC-1234');
});

it('returns 401 when showing a car without authentication', function () {
    $car = Car::factory()->create();
    $response = $this->getJson('/api/cars/'.$car->id);
    $response->assertStatus(401);
});

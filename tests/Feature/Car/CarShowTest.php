<?php

declare(strict_types=1);

use App\Models\Car;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can show a car', function () {
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

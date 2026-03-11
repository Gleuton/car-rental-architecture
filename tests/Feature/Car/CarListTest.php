<?php

declare(strict_types=1);

use App\Models\Car;
use App\Models\CarModel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

uses(RefreshDatabase::class);

it('can list cars one page', function () {
    Auth::guard('api')->login(User::factory()->create());
    Car::factory()->count(15)->create();

    $response = $this->getJson('/api/cars');
    $response->assertStatus(200)
        ->assertJsonCount(15, 'data')
        ->assertJsonPath('meta.current_page', 1)
        ->assertJsonPath('meta.per_page', 15)
        ->assertJsonPath('meta.total', 15)
        ->assertJsonPath('meta.last_page', 1)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'licensePlate',
                    'color',
                    'km',
                    'carModelId',
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

it('can list cars with custom per_page parameter', function () {
    Auth::guard('api')->login(User::factory()->create());
    Car::factory()->count(30)->create();

    $response = $this->getJson('/api/cars?per_page=10');
    $response->assertStatus(200)
        ->assertJsonCount(10, 'data')
        ->assertJsonPath('meta.per_page', 10)
        ->assertJsonPath('meta.total', 30)
        ->assertJsonPath('meta.last_page', 3);
});

it('returns empty list when no cars exist', function () {
    Auth::guard('api')->login(User::factory()->create());
    $response = $this->getJson('/api/cars');
    $response->assertStatus(200)
        ->assertJsonCount(0, 'data')
        ->assertJsonPath('meta.current_page', 1)
        ->assertJsonPath('meta.total', 0)
        ->assertJsonPath('meta.last_page', 1);
});

it('can filter cars by license plate', function () {
    Auth::guard('api')->login(User::factory()->create());
    Car::factory()->create(['license_plate' => 'ABC-1234']);
    Car::factory()->create(['license_plate' => 'XYZ-5678']);
    Car::factory()->count(10)->create();

    $response = $this->getJson('/api/cars?license_plate=ABC-1234');
    $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.licensePlate', 'ABC-1234')
        ->assertJsonPath('meta.total', 1);
});

it('can filter cars by license plate with pagination', function () {
    Auth::guard('api')->login(User::factory()->create());
    Car::factory()->create(['license_plate' => 'TEST-0001']);
    Car::factory()->create(['license_plate' => 'TEST-0002']);
    Car::factory()->create(['license_plate' => 'TEST-0003']);
    Car::factory()->count(10)->create();

    $response = $this->getJson('/api/cars?license_plate=TEST&per_page=2&page=1');
    $response->assertStatus(200)
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('meta.total', 3)
        ->assertJsonPath('meta.per_page', 2)
        ->assertJsonPath('meta.last_page', 2);
});

it('returns empty when filtering by non-existent license plate', function () {
    Auth::guard('api')->login(User::factory()->create());
    Car::factory()->count(10)->create();

    $response = $this->getJson('/api/cars?license_plate=NONEXISTENT-9999');
    $response->assertStatus(200)
        ->assertJsonCount(0, 'data')
        ->assertJsonPath('meta.total', 0);
});

it('can order cars by different fields', function () {
    Auth::guard('api')->login(User::factory()->create());
    CarModel::factory()->create();
    Car::factory()->create(['id' => 1, 'license_plate' => 'ZZZ-9999']);
    Car::factory()->create(['id' => 2, 'license_plate' => 'AAA-1111']);
    Car::factory()->create(['id' => 3, 'license_plate' => 'MMM-5555']);

    $response = $this->getJson('/api/cars?order_by=id&direction=asc');
    $response->assertStatus(200)
        ->assertJsonPath('data.0.id', 1)
        ->assertJsonPath('data.1.id', 2)
        ->assertJsonPath('data.2.id', 3);

    $response = $this->getJson('/api/cars?order_by=id&direction=desc');
    $response->assertStatus(200)
        ->assertJsonPath('data.0.id', 3)
        ->assertJsonPath('data.1.id', 2)
        ->assertJsonPath('data.2.id', 1);
});

it('uses default sorting when order_by is not provided', function () {
    Auth::guard('api')->login(User::factory()->create());
    Car::factory()->count(3)->create();

    $response = $this->getJson('/api/cars');
    $response->assertStatus(200)
        ->assertJsonCount(3, 'data');
});

it('respects per_page limit of 100', function () {
    Auth::guard('api')->login(User::factory()->create());
    Car::factory()->count(50)->create();

    $response = $this->getJson('/api/cars?per_page=100');
    $response->assertStatus(200)
        ->assertJsonCount(50, 'data')
        ->assertJsonPath('meta.per_page', 100);
});

it('handles invalid per_page parameter gracefully', function (mixed $perPage) {
    Auth::guard('api')->login(User::factory()->create());
    Car::factory()->count(15)->create();

    $response = $this->getJson('/api/cars?per_page='.$perPage);
    $response->assertStatus(422);
})->with([
    'string' => ['perPage' => 'invalid'],
    'negative' => ['perPage' => -1],
    'float' => ['perPage' => 1.5],
    'zero' => ['perPage' => 0],
    'big' => ['perPage' => 101],
]);

it('handles invalid page parameter gracefully', function (mixed $page) {
    Auth::guard('api')->login(User::factory()->create());
    Car::factory()->count(15)->create();

    $response = $this->getJson('/api/cars?page='.$page);
    $response->assertStatus(422);
})->with([
    'string' => ['page' => 'invalid'],
    'negative' => ['page' => -1],
    'float' => ['page' => 1.5],
]);

it('handles invalid direction parameter gracefully', function () {
    Auth::guard('api')->login(User::factory()->create());
    Car::factory()->count(15)->create();

    $response = $this->getJson('/api/cars?direction=invalid');
    $response->assertStatus(422);
});

it('handles invalid order_by parameter gracefully', function () {
    Auth::guard('api')->login(User::factory()->create());
    Car::factory()->count(15)->create();

    $response = $this->getJson('/api/cars?order_by=invalid_field');
    $response->assertStatus(422);
});

it('response contains all required car fields', function () {
    Auth::guard('api')->login(User::factory()->create());
    Car::factory()->create();

    $response = $this->getJson('/api/cars');
    $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'licensePlate',
                    'color',
                    'km',
                    'carModelId',
                ],
            ],
        ]);
});

it('returns 200 when requesting page beyond available data', function () {
    Auth::guard('api')->login(User::factory()->create());
    Car::factory()->count(15)->create();

    $response = $this->getJson('/api/cars?page=100&per_page=10');
    $response->assertStatus(200)
        ->assertJsonCount(0, 'data')
        ->assertJsonPath('meta.current_page', 100)
        ->assertJsonPath('meta.total', 15);
});

it('can combine filter with sorting', function () {
    Auth::guard('api')->login(User::factory()->create());
    Car::factory()->create(['license_plate' => 'SORT-0001']);
    Car::factory()->create(['license_plate' => 'SORT-0002']);
    Car::factory()->create(['license_plate' => 'SORT-0003']);
    Car::factory()->count(10)->create();

    $response = $this->getJson('/api/cars?license_plate=SORT&order_by=id&direction=desc');
    $response->assertStatus(200)
        ->assertJsonCount(3, 'data')
        ->assertJsonPath('meta.total', 3);
});

it('returns 401 when listing cars without authentication', function () {
    $response = $this->getJson('/api/cars');
    $response->assertStatus(401);
});

it('handles default per_page value correctly', function () {
    authenticateApi();
    Car::factory()->count(20)->create();

    $response = $this->getJson('/api/cars');
    $response->assertStatus(200)
        ->assertJsonCount(15, 'data')
        ->assertJsonPath('meta.per_page', 15)
        ->assertJsonPath('meta.total', 20)
        ->assertJsonPath('meta.last_page', 2);
});

it('handles boundary case with per_page=1', function () {
    authenticateApi();
    Car::factory()->count(5)->create();

    $response = $this->getJson('/api/cars?per_page=1');
    $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('meta.per_page', 1)
        ->assertJsonPath('meta.total', 5)
        ->assertJsonPath('meta.last_page', 5);
});

it('validates request structure with empty parameters', function () {
    Auth::guard('api')->login(User::factory()->create());
    Car::factory()->count(15)->create();

    $response = $this->getJson('/api/cars?license_plate=&order_by=&direction=');
    $response->assertStatus(200)
        ->assertJsonCount(15, 'data');
});

it('can access specific page with custom per_page', function () {
    Auth::guard('api')->login(User::factory()->create());
    Car::factory()->count(25)->create();

    $response = $this->getJson('/api/cars?page=2&per_page=12');
    $response->assertStatus(200)
        ->assertJsonCount(12, 'data')
        ->assertJsonPath('meta.current_page', 2)
        ->assertJsonPath('meta.per_page', 12)
        ->assertJsonPath('meta.last_page', 3);
});

it('can filter by partial license plate match', function () {
    Auth::guard('api')->login(User::factory()->create());
    Car::factory()->create(['license_plate' => 'ABC-1234']);
    Car::factory()->create(['license_plate' => 'ABC-5678']);
    Car::factory()->create(['license_plate' => 'XYZ-1234']);

    $response = $this->getJson('/api/cars?license_plate=ABC');
    $response->assertStatus(200)
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('meta.total', 2);
});

it('default page is 1 when not specified', function () {
    Auth::guard('api')->login(User::factory()->create());
    Car::factory()->count(20)->create();

    $response = $this->getJson('/api/cars?per_page=5');
    $response->assertStatus(200)
        ->assertJsonPath('meta.current_page', 1);
});

it('returns correct total count with filters applied', function () {
    Auth::guard('api')->login(User::factory()->create());
    Car::factory()->create(['license_plate' => 'TOTAL-001']);
    Car::factory()->create(['license_plate' => 'TOTAL-002']);
    Car::factory()->create(['license_plate' => 'OTHER-001']);

    $response = $this->getJson('/api/cars?license_plate=TOTAL');
    $response->assertStatus(200)
        ->assertJsonPath('meta.total', 2);

    $response = $this->getJson('/api/cars?license_plate=OTHER');
    $response->assertStatus(200)
        ->assertJsonPath('meta.total', 1);
});

it('can order by name field', function () {
    Auth::guard('api')->login(User::factory()->create());
    Car::factory()->create(['license_plate' => 'Z-PLATE']);
    Car::factory()->create(['license_plate' => 'A-PLATE']);
    Car::factory()->create(['license_plate' => 'M-PLATE']);

    $response = $this->getJson('/api/cars?order_by=name&direction=asc');
    $response->assertStatus(200)
        ->assertJsonCount(3, 'data');
});

it('returns correct last_page calculation', function () {
    Auth::guard('api')->login(User::factory()->create());
    Car::factory()->count(37)->create();

    $response = $this->getJson('/api/cars?per_page=10');
    $response->assertStatus(200)
        ->assertJsonPath('meta.total', 37)
        ->assertJsonPath('meta.last_page', 4);
});

it('first page shows different data than second page', function () {
    Auth::guard('api')->login(User::factory()->create());
    $cars = Car::factory()->count(5)->create();

    $response1 = $this->getJson('/api/cars?per_page=2&page=1');
    $response2 = $this->getJson('/api/cars?per_page=2&page=2');

    $firstPageIds = array_map(static fn ($car) => $car['id'], $response1->json('data'));
    $secondPageIds = array_map(static fn ($car) => $car['id'], $response2->json('data'));

    expect($firstPageIds)->not->toBe($secondPageIds);
});

it('provides pagination metadata for single item per page', function () {
    Auth::guard('api')->login(User::factory()->create());
    Car::factory()->count(3)->create();

    $response = $this->getJson('/api/cars?per_page=1');
    $response->assertStatus(200)
        ->assertJsonPath('meta.per_page', 1)
        ->assertJsonPath('meta.total', 3)
        ->assertJsonPath('meta.last_page', 3)
        ->assertJsonCount(1, 'data');
});

it('can list all cars without filters in single request', function () {
    Auth::guard('api')->login(User::factory()->create());
    Car::factory()->count(100)->create();

    $response = $this->getJson('/api/cars?per_page=100');
    $response->assertStatus(200)
        ->assertJsonCount(100, 'data')
        ->assertJsonPath('meta.total', 100)
        ->assertJsonPath('meta.per_page', 100)
        ->assertJsonPath('meta.last_page', 1);
});

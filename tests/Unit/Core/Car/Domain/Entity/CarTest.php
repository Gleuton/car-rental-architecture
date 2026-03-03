<?php

declare(strict_types=1);

use App\Core\Car\Domain\Entity\Car;
use App\Core\Car\Domain\Exceptions\CarDomainException;

it('can create a Car instance', function () {
    $car = Car::new(
        carModelId: 1,
        licensePlate: 'ABC-1234',
        color: 'Red',
        isAvailable: true,
        km: 10000
    );

    expect($car->carModelId)->toBe(1)
        ->and($car->licensePlate)->toBe('ABC-1234')
        ->and($car->color)->toBe('Red')
        ->and($car->isAvailable)->toBeTrue()
        ->and($car->km)->toBe(10000)
        ->and($car->id)->toBeNull();
});

it('can restore a Car instance with ID', function () {
    $car = Car::restore(
        id: 1,
        car_model_id: 1,
        license_plate: 'ABC-1234',
        color: 'Red',
        is_available: true,
        km: 10000
    );

    expect($car->id)->toBe(1)
        ->and($car->carModelId)->toBe(1)
        ->and($car->licensePlate)->toBe('ABC-1234')
        ->and($car->color)->toBe('Red')
        ->and($car->isAvailable)->toBeTrue()
        ->and($car->km)->toBe(10000);
});

it('can update a Car instance', function () {
    $car = Car::new(
        carModelId: 1,
        licensePlate: 'ABC-1234',
        color: 'Red',
        isAvailable: true,
        km: 10000
    );

    $updatedCar = $car->update(
        carModelId: 2,
        licensePlate: 'XYZ-5678',
        color: 'Blue',
        isAvailable: false,
        km: 20000
    );

    expect($updatedCar->carModelId)->toBe(2)
        ->and($updatedCar->licensePlate)->toBe('XYZ-5678')
        ->and($updatedCar->color)->toBe('Blue')
        ->and($updatedCar->isAvailable)->toBeFalse()
        ->and($updatedCar->km)->toBe(20000);
});

it('can partially update a Car instance', function () {
    $car = Car::new(
        carModelId: 1,
        licensePlate: 'ABC-1234',
        color: 'Red',
        isAvailable: true,
        km: 10000
    );

    $updatedCar = $car->update(color: 'Blue', km: 15000);

    expect($updatedCar->carModelId)->toBe(1)
        ->and($updatedCar->licensePlate)->toBe('ABC-1234')
        ->and($updatedCar->color)->toBe('Blue')
        ->and($updatedCar->isAvailable)->toBeTrue()
        ->and($updatedCar->km)->toBe(15000);
});

it('throws exception when creating Car with empty license plate', function () {
    Car::new(
        carModelId: 1,
        licensePlate: '',
        color: 'Red',
        isAvailable: true,
        km: 10000
    );
})->throws(CarDomainException::class, 'License plate cannot be empty');

it('throws exception when creating Car with whitespace-only license plate', function () {
    Car::new(
        carModelId: 1,
        licensePlate: '   ',
        color: 'Red',
        isAvailable: true,
        km: 10000
    );
})->throws(CarDomainException::class, 'License plate cannot be empty');

it('throws exception when creating Car with license plate too short', function () {
    Car::new(
        carModelId: 1,
        licensePlate: 'ABC123',
        color: 'Red',
        isAvailable: true,
        km: 10000
    );
})->throws(CarDomainException::class, 'License plate must have at least 7 characters');

it('throws exception when creating Car with license plate too long', function () {
    Car::new(
        carModelId: 1,
        licensePlate: 'ABC-1234567',
        color: 'Red',
        isAvailable: true,
        km: 10000
    );
})->throws(CarDomainException::class, 'License plate cannot exceed 10 characters');

it('accepts license plate with exactly 7 characters', function () {
    $car = Car::new(
        carModelId: 1,
        licensePlate: 'ABC1234',
        color: 'Red',
        isAvailable: true,
        km: 10000
    );

    expect($car->licensePlate)->toBe('ABC1234');
});

it('accepts license plate with exactly 10 characters', function () {
    $car = Car::new(
        carModelId: 1,
        licensePlate: 'ABC-123456',
        color: 'Red',
        isAvailable: true,
        km: 10000
    );

    expect($car->licensePlate)->toBe('ABC-123456');
});

it('throws exception when creating Car with empty color', function () {
    Car::new(
        carModelId: 1,
        licensePlate: 'ABC-1234',
        color: '',
        isAvailable: true,
        km: 10000
    );
})->throws(CarDomainException::class, 'Color cannot be empty');

it('throws exception when creating Car with whitespace-only color', function () {
    Car::new(
        carModelId: 1,
        licensePlate: 'ABC-1234',
        color: '   ',
        isAvailable: true,
        km: 10000
    );
})->throws(CarDomainException::class, 'Color cannot be empty');

it('throws exception when creating Car with color too short', function () {
    Car::new(
        carModelId: 1,
        licensePlate: 'ABC-1234',
        color: 'AB',
        isAvailable: true,
        km: 10000
    );
})->throws(CarDomainException::class, 'Color must have at least 3 characters');

it('throws exception when creating Car with color too long', function () {
    Car::new(
        carModelId: 1,
        licensePlate: 'ABC-1234',
        color: str_repeat('A', 51),
        isAvailable: true,
        km: 10000
    );
})->throws(CarDomainException::class, 'Color cannot exceed 50 characters');

it('accepts color with exactly 3 characters', function () {
    $car = Car::new(
        carModelId: 1,
        licensePlate: 'ABC-1234',
        color: 'Red',
        isAvailable: true,
        km: 10000
    );

    expect($car->color)->toBe('Red');
});

it('accepts color with exactly 50 characters', function () {
    $color = str_repeat('A', 50);
    $car = Car::new(
        carModelId: 1,
        licensePlate: 'ABC-1234',
        color: $color,
        isAvailable: true,
        km: 10000
    );

    expect($car->color)->toBe($color);
});

it('throws exception when creating Car with negative km', function () {
    Car::new(
        carModelId: 1,
        licensePlate: 'ABC-1234',
        color: 'Red',
        isAvailable: true,
        km: -1
    );
})->throws(CarDomainException::class, 'Kilometers must be zero or positive');

it('accepts Car with zero km', function () {
    $car = Car::new(
        carModelId: 1,
        licensePlate: 'ABC-1234',
        color: 'Red',
        isAvailable: true,
        km: 0
    );

    expect($car->km)->toBe(0);
});

it('accepts Car with positive km', function () {
    $car = Car::new(
        carModelId: 1,
        licensePlate: 'ABC-1234',
        color: 'Red',
        isAvailable: true,
        km: 100000
    );

    expect($car->km)->toBe(100000);
});

it('validates license plate when updating', function () {
    $car = Car::new(
        carModelId: 1,
        licensePlate: 'ABC-1234',
        color: 'Red',
        isAvailable: true,
        km: 10000
    );

    $car->update(licensePlate: 'ABC');
})->throws(CarDomainException::class, 'License plate must have at least 7 characters');

it('validates color when updating', function () {
    $car = Car::new(
        carModelId: 1,
        licensePlate: 'ABC-1234',
        color: 'Red',
        isAvailable: true,
        km: 10000
    );

    $car->update(color: 'AB');
})->throws(CarDomainException::class, 'Color must have at least 3 characters');

it('validates km when updating', function () {
    $car = Car::new(
        carModelId: 1,
        licensePlate: 'ABC-1234',
        color: 'Red',
        isAvailable: true,
        km: 10000
    );

    $car->update(km: -500);
})->throws(CarDomainException::class, 'Kilometers must be zero or positive');

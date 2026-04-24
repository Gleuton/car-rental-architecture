<?php

declare(strict_types=1);

use App\Core\Car\Domain\Entities\Car;
use App\Core\Car\Domain\Exceptions\CarDomainException;
use Illuminate\Support\Str;

const TEST_CAR_MODEL_UUID = '22222222-2222-4222-8222-222222222222';

it('can create a Car instance', function () {
    $car = Car::new(
        TEST_CAR_MODEL_UUID,
        'ABC-1234',
        'Red',
        true,
        10000
    );

    expect($car->carModelUuid)->toBe(TEST_CAR_MODEL_UUID)
        ->and($car->licensePlate())->toBe('ABC-1234')
        ->and($car->color())->toBe('Red')
        ->and($car->isAvailable())->toBeTrue()
        ->and($car->km())->toBe(10000)
        ->and(Str::isUuid($car->uuid))->toBeTrue();
});

it('can restore a Car instance with uuid', function () {
    $car = Car::restore(
        TEST_CAR_MODEL_UUID,
        'ABC-1234',
        'Red',
        true,
        10000
    );

    expect($car->carModelUuid)->toBe(TEST_CAR_MODEL_UUID)
        ->and($car->licensePlate())->toBe('ABC-1234')
        ->and($car->color())->toBe('Red')
        ->and($car->isAvailable())->toBeTrue()
        ->and($car->km())->toBe(10000)
        ->and(Str::isUuid($car->uuid))->toBeTrue();
});

it('can change license plate', function () {
    $car = Car::new(
        TEST_CAR_MODEL_UUID,
        'ABC-1234',
        'Red',
        true,
        10000
    );

    $updatedCar = $car->changeLicensePlate('XYZ-5678');

    expect($updatedCar->licensePlate())->toBe('XYZ-5678')
        ->and($updatedCar->carModelUuid)->toBe(TEST_CAR_MODEL_UUID)
        ->and($updatedCar->color())->toBe('Red')
        ->and($updatedCar->isAvailable())->toBeTrue()
        ->and($updatedCar->km())->toBe(10000)
        ->and($updatedCar->uuid)->toBe($car->uuid);
});

it('can change color', function () {
    $car = Car::new(
        TEST_CAR_MODEL_UUID,
        'ABC-1234',
        'Red',
        true,
        10000
    );

    $updatedCar = $car->changeColor('Blue');

    expect($updatedCar->carModelUuid)->toBe(TEST_CAR_MODEL_UUID)
        ->and($updatedCar->licensePlate())->toBe('ABC-1234')
        ->and($updatedCar->color())->toBe('Blue')
        ->and($updatedCar->isAvailable())->toBeTrue()
        ->and($updatedCar->km())->toBe(10000)
        ->and($updatedCar->uuid)->toBe($car->uuid);
});

it('can mark a car as unavailable', function () {
    $car = Car::new(
        TEST_CAR_MODEL_UUID,
        'ABC-1234',
        'Red',
        true,
        10000
    );

    $updatedCar = $car->markAsUnavailable();

    expect($updatedCar->isAvailable())->toBeFalse();
    expect($updatedCar->uuid)->toBe($car->uuid);
});

it('can mark a car as available', function () {
    $car = Car::new(
        TEST_CAR_MODEL_UUID,
        'ABC-1234',
        'Red',
        false,
        10000
    );

    $updatedCar = $car->markAsAvailable();

    expect($updatedCar->isAvailable())->toBeTrue();
    expect($updatedCar->uuid)->toBe($car->uuid);
});

it('throws exception when creating Car with empty license plate', function () {
    Car::new(
        TEST_CAR_MODEL_UUID,
        '',
        'Red',
        true,
        10000
    );
})->throws(CarDomainException::class, 'License plate cannot be empty');

it('throws exception when creating Car with whitespace-only license plate', function () {
    Car::new(
        TEST_CAR_MODEL_UUID,
        '   ',
        'Red',
        true,
        10000
    );
})->throws(CarDomainException::class, 'License plate cannot be empty');

it('throws exception when creating Car with license plate too short', function () {
    Car::new(
        TEST_CAR_MODEL_UUID,
        'ABC123',
        'Red',
        true,
        10000
    );
})->throws(CarDomainException::class, 'License plate must have at least 7 characters');

it('throws exception when creating Car with license plate too long', function () {
    Car::new(
        TEST_CAR_MODEL_UUID,
        'ABC-1234567',
        'Red',
        true,
        10000
    );
})->throws(CarDomainException::class, 'License plate cannot exceed 10 characters');

it('accepts license plate with exactly 7 characters', function () {
    $car = Car::new(
        TEST_CAR_MODEL_UUID,
        'ABC1234',
        'Red',
        true,
        10000
    );

    expect($car->licensePlate())->toBe('ABC1234');
});

it('accepts license plate with exactly 10 characters', function () {
    $car = Car::new(
        TEST_CAR_MODEL_UUID,
        'ABC-123456',
        'Red',
        true,
        10000
    );

    expect($car->licensePlate())->toBe('ABC-123456');
});

it('throws exception when creating Car with empty color', function () {
    Car::new(
        TEST_CAR_MODEL_UUID,
        'ABC-1234',
        '',
        true,
        10000
    );
})->throws(CarDomainException::class, 'Color cannot be empty');

it('throws exception when creating Car with whitespace-only color', function () {
    Car::new(
        TEST_CAR_MODEL_UUID,
        'ABC-1234',
        '   ',
        true,
        10000
    );
})->throws(CarDomainException::class, 'Color cannot be empty');

it('throws exception when creating Car with color too short', function () {
    Car::new(
        TEST_CAR_MODEL_UUID,
        'ABC-1234',
        'AB',
        true,
        10000
    );
})->throws(CarDomainException::class, 'Color must have at least 3 characters');

it('throws exception when creating Car with color too long', function () {
    Car::new(
        TEST_CAR_MODEL_UUID,
        'ABC-1234',
        str_repeat('A', 51),
        true,
        10000
    );
})->throws(CarDomainException::class, 'Color cannot exceed 50 characters');

it('accepts color with exactly 3 characters', function () {
    $car = Car::new(
        TEST_CAR_MODEL_UUID,
        'ABC-1234',
        'Red',
        true,
        10000
    );

    expect($car->color())->toBe('Red');
});

it('accepts color with exactly 50 characters', function () {
    $color = str_repeat('A', 50);
    $car = Car::new(
        TEST_CAR_MODEL_UUID,
        'ABC-1234',
        $color,
        true,
        10000
    );

    expect($car->color())->toBe($color);
});

it('throws exception when creating Car with negative km', function () {
    Car::new(
        TEST_CAR_MODEL_UUID,
        'ABC-1234',
        'Red',
        true,
        -1
    );
})->throws(CarDomainException::class, 'Kilometers must be zero or positive');

it('accepts Car with zero km', function () {
    $car = Car::new(
        TEST_CAR_MODEL_UUID,
        'ABC-1234',
        'Red',
        true,
        0
    );

    expect($car->km())->toBe(0);
});

it('accepts Car with positive km', function () {
    $car = Car::new(
        TEST_CAR_MODEL_UUID,
        'ABC-1234',
        'Red',
        true,
        100000
    );

    expect($car->km())->toBe(100000);
});

it('validates license plate when changing license plate', function () {
    $car = Car::new(
        TEST_CAR_MODEL_UUID,
        'ABC-1234',
        'Red',
        true,
        10000
    );

    $car->changeLicensePlate('ABC');
})->throws(CarDomainException::class, 'License plate must have at least 7 characters');

it('validates color when changing color', function () {
    $car = Car::new(
        TEST_CAR_MODEL_UUID,
        'ABC-1234',
        'Red',
        true,
        10000
    );

    $car->changeColor('AB');
})->throws(CarDomainException::class, 'Color must have at least 3 characters');

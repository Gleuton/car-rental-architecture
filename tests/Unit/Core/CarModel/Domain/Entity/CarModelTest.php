<?php

declare(strict_types=1);

use App\Core\CarModel\Domain\Entity\CarModel;
use App\Core\CarModel\Domain\Exceptions\CarModelDomainException;

it('can create a CarModel instance', function () {
    $carModel = CarModel::new(
        brandId: 1,
        name: 'Civic',
        image: 'civic.png',
        doorsNumber: 4,
        seatsNumber: 5,
        airbags: true,
        abs: true
    );

    expect($carModel->brandId)->toBe(1)
        ->and($carModel->name)->toBe('Civic')
        ->and($carModel->image)->toBe('civic.png')
        ->and($carModel->doorsNumber)->toBe(4)
        ->and($carModel->seatsNumber)->toBe(5)
        ->and($carModel->airbags)->toBeTrue()
        ->and($carModel->abs)->toBeTrue()
        ->and($carModel->id)->toBeNull();
});

it('can create a CarModel instance with ID', function () {
    $carModel = CarModel::restore(
        id: 1,
        brandId: 1,
        name: 'Civic',
        image: 'civic.png',
        doorsNumber: 4,
        seatsNumber: 5,
        airbags: true,
        abs: true
    );

    expect($carModel->id)->toBe(1)
        ->and($carModel->brandId)->toBe(1)
        ->and($carModel->name)->toBe('Civic')
        ->and($carModel->image)->toBe('civic.png')
        ->and($carModel->doorsNumber)->toBe(4)
        ->and($carModel->seatsNumber)->toBe(5)
        ->and($carModel->airbags)->toBeTrue()
        ->and($carModel->abs)->toBeTrue();
});

it('throws exception when creating CarModel with seats number less than 2', function () {
    CarModel::new(
        brandId: 1,
        name: 'Civic',
        image: 'civic.png',
        doorsNumber: 4,
        seatsNumber: 1,
        airbags: true,
        abs: true
    );
})->throws(CarModelDomainException::class, 'Seats number must be between 2 and 7');

it('throws exception when creating CarModel with seats number greater than 7', function () {
    CarModel::new(
        brandId: 1,
        name: 'Civic',
        image: 'civic.png',
        doorsNumber: 4,
        seatsNumber: 8,
        airbags: true,
        abs: true
    );
})->throws(CarModelDomainException::class, 'Seats number must be between 2 and 7');

it('throws exception when creating CarModel with doors number less than 2', function () {
    CarModel::new(
        brandId: 1,
        name: 'Civic',
        image: 'civic.png',
        doorsNumber: 1,
        seatsNumber: 5,
        airbags: true,
        abs: true
    );
})->throws(CarModelDomainException::class, 'Doors number must be between 2 and 5');

it('throws exception when creating CarModel with doors number greater than 5', function () {
    CarModel::new(
        brandId: 1,
        name: 'Civic',
        image: 'civic.png',
        doorsNumber: 6,
        seatsNumber: 5,
        airbags: true,
        abs: true
    );
})->throws(CarModelDomainException::class, 'Doors number must be between 2 and 5');

it('allows creating CarModel with minimum valid values', function () {
    $carModel = CarModel::new(
        brandId: 1,
        name: 'Compact',
        image: 'compact.png',
        doorsNumber: 2,
        seatsNumber: 2,
        airbags: false,
        abs: false
    );

    expect($carModel->doorsNumber)->toBe(2)
        ->and($carModel->seatsNumber)->toBe(2)
        ->and($carModel->airbags)->toBeFalse()
        ->and($carModel->abs)->toBeFalse();
});

it('allows creating CarModel with maximum valid values', function () {
    $carModel = CarModel::new(
        brandId: 1,
        name: 'Van',
        image: 'van.png',
        doorsNumber: 5,
        seatsNumber: 7,
        airbags: true,
        abs: true
    );

    expect($carModel->doorsNumber)->toBe(5)
        ->and($carModel->seatsNumber)->toBe(7);
});

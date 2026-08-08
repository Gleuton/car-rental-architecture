<?php

declare(strict_types=1);

use App\Core\Car\Domain\Entity\CarModel;
use App\Core\Car\Domain\Exceptions\CarModelDomainException;
use Illuminate\Support\Str;

it('can create a CarModel instance', function () {
    $carModel = CarModel::new(
        brandUuid: '11111111-1111-4111-8111-111111111111',
        name: 'Civic',
        image: 'civic.png',
        doorsNumber: 4,
        seatsNumber: 5,
        airbags: true,
        abs: true
    );

    expect($carModel->brandUuid)->toBe('11111111-1111-4111-8111-111111111111')
        ->and(Str::isUuid($carModel->uuid))->toBeTrue()
        ->and($carModel->name)->toBe('Civic')
        ->and($carModel->image)->toBe('civic.png')
        ->and($carModel->doorsNumber)->toBe(4)
        ->and($carModel->seatsNumber)->toBe(5)
        ->and($carModel->airbags)->toBeTrue()
        ->and($carModel->abs)->toBeTrue();
});

it('can restore a CarModel instance with uuid', function () {
    $uuid = (string) Str::uuid();
    $carModel = CarModel::restore(
        brandUuid: '11111111-1111-4111-8111-111111111111',
        name: 'Civic',
        image: 'civic.png',
        doorsNumber: 4,
        seatsNumber: 5,
        airbags: true,
        abs: true,
        uuid: $uuid,
    );

    expect($carModel->uuid)->toBe($uuid)
        ->and($carModel->brandUuid)->toBe('11111111-1111-4111-8111-111111111111')
        ->and($carModel->name)->toBe('Civic')
        ->and($carModel->image)->toBe('civic.png')
        ->and($carModel->doorsNumber)->toBe(4)
        ->and($carModel->seatsNumber)->toBe(5)
        ->and($carModel->airbags)->toBeTrue()
        ->and($carModel->abs)->toBeTrue();
});

it('throws exception when creating CarModel with seats number less than 2', function () {
    CarModel::new(
        brandUuid: '11111111-1111-4111-8111-111111111111',
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
        brandUuid: '11111111-1111-4111-8111-111111111111',
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
        brandUuid: '11111111-1111-4111-8111-111111111111',
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
        brandUuid: '11111111-1111-4111-8111-111111111111',
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
        brandUuid: '11111111-1111-4111-8111-111111111111',
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
        brandUuid: '11111111-1111-4111-8111-111111111111',
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

it('can update all fields of a CarModel', function () {
    $carModel = CarModel::restore(
        brandUuid: '11111111-1111-4111-8111-111111111111',
        name: 'Civic',
        image: 'civic.png',
        doorsNumber: 4,
        seatsNumber: 5,
        airbags: true,
        abs: true
    );

    $updated = $carModel->update(
        brandUuid: '22222222-2222-4222-8222-222222222222',
        name: 'Corolla',
        image: 'corolla.png',
        doorsNumber: 5,
        seatsNumber: 7,
        airbags: false,
        abs: false
    );

    expect($updated->uuid)->toBe($carModel->uuid)
        ->and($updated->brandUuid)->toBe('22222222-2222-4222-8222-222222222222')
        ->and($updated->name)->toBe('Corolla')
        ->and($updated->image)->toBe('corolla.png')
        ->and($updated->doorsNumber)->toBe(5)
        ->and($updated->seatsNumber)->toBe(7)
        ->and($updated->airbags)->toBeFalse()
        ->and($updated->abs)->toBeFalse();
});

it('can update CarModel partially keeping other fields', function () {
    $carModel = CarModel::restore(
        brandUuid: '11111111-1111-4111-8111-111111111111',
        name: 'Civic',
        image: 'civic.png',
        doorsNumber: 4,
        seatsNumber: 5,
        airbags: true,
        abs: true
    );

    $updated = $carModel->update(
        brandUuid: null,
        name: 'Civic Sport',
        image: 'civic_sport.png',
        doorsNumber: null,
        seatsNumber: null,
        airbags: null,
        abs: null
    );

    expect($updated->uuid)->toBe($carModel->uuid)
        ->and($updated->brandUuid)->toBe('11111111-1111-4111-8111-111111111111')
        ->and($updated->name)->toBe('Civic Sport')
        ->and($updated->image)->toBe('civic_sport.png')
        ->and($updated->doorsNumber)->toBe(4)
        ->and($updated->seatsNumber)->toBe(5)
        ->and($updated->airbags)->toBeTrue()
        ->and($updated->abs)->toBeTrue();
});

it('throws exception when updating CarModel with invalid doors number', function () {
    $carModel = CarModel::restore(
        brandUuid: '11111111-1111-4111-8111-111111111111',
        name: 'Civic',
        image: 'civic.png',
        doorsNumber: 4,
        seatsNumber: 5,
        airbags: true,
        abs: true
    );

    $carModel->update(
        brandUuid: null,
        name: null,
        image: 'civic.png',
        doorsNumber: 6,
        seatsNumber: null,
        airbags: null,
        abs: null
    );
})->throws(CarModelDomainException::class, 'Doors number must be between 2 and 5');

it('throws exception when updating CarModel with invalid seats number', function () {
    $carModel = CarModel::restore(
        brandUuid: '11111111-1111-4111-8111-111111111111',
        name: 'Civic',
        image: 'civic.png',
        doorsNumber: 4,
        seatsNumber: 5,
        airbags: true,
        abs: true
    );

    $carModel->update(
        brandUuid: null,
        name: null,
        image: 'civic.png',
        doorsNumber: null,
        seatsNumber: 8,
        airbags: null,
        abs: null
    );
})->throws(CarModelDomainException::class, 'Seats number must be between 2 and 7');

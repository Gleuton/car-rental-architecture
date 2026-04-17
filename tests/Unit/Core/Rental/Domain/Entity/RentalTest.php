<?php

declare(strict_types=1);

use App\Core\Rental\Domain\Entity\Rental;
use App\Core\Rental\Domain\Errors\RentalError;
use App\Core\Rental\Domain\Exceptions\RentalDomainException;
use Illuminate\Support\Str;

it('can create a Rental instance', function () {
    $rental = Rental::new(
        carId: 1,
        carUuid: (string) Str::uuid(),
        clientId: 1,
        clientUuid: (string) Str::uuid(),
        dayPriceCents: 5000,
        startDate: '2026-03-09 10:00:00',
        endDate: '2026-03-19 10:00:00',
        initialKm: 1000,
        finalKm: 1500,
    );

    expect($rental->carId)->toBe(1)
        ->and($rental->clientId)->toBe(1)
        ->and($rental->dayPriceCents)->toBe(5000)
        ->and($rental->startDate)->toBe('2026-03-09 10:00:00')
        ->and($rental->endDate)->toBe('2026-03-19 10:00:00')
        ->and($rental->initialKm)->toBe(1000)
        ->and($rental->finalKm)->toBe(1500)
        ->and($rental->id)->toBeNull()
        ->and(Str::isUuid($rental->uuid))->toBeTrue();
});

it('can restore a Rental instance with ID', function () {
    $rental = Rental::restore(
        id: 1,
        carId: 1,
        carUuid: (string) Str::uuid(),
        clientId: 1,
        clientUuid: (string) Str::uuid(),
        dayPriceCents: 5000,
        startDate: '2026-03-09 10:00:00',
        endDate: '2026-03-19 10:00:00',
        initialKm: 1000,
        finalKm: 1500,
    );

    expect($rental->id)->toBe(1)
        ->and($rental->carId)->toBe(1)
        ->and($rental->clientId)->toBe(1)
        ->and($rental->dayPriceCents)->toBe(5000)
        ->and($rental->startDate)->toBe('2026-03-09 10:00:00')
        ->and($rental->endDate)->toBe('2026-03-19 10:00:00')
        ->and($rental->initialKm)->toBe(1000)
        ->and($rental->finalKm)->toBe(1500)
        ->and(Str::isUuid($rental->uuid))->toBeTrue();
});

it('throws RentalDomainException when start date has invalid format', function () {
    Rental::new(
        carId: 1,
        carUuid: (string) Str::uuid(),
        clientId: 1,
        clientUuid: (string) Str::uuid(),
        dayPriceCents: 5000,
        startDate: '2026-03-09',
        endDate: '2026-03-19 10:00:00',
        initialKm: 1000,
        finalKm: 1500,
    );
})->throws(
    RentalDomainException::class,
    RentalError::INVALID_DATE_FORMAT->message(),
    RentalError::INVALID_DATE_FORMAT->value
);

it('throws RentalDomainException when end date has invalid format', function () {
    Rental::new(
        carId: 1,
        carUuid: (string) Str::uuid(),
        clientId: 1,
        clientUuid: (string) Str::uuid(),
        dayPriceCents: 5000,
        startDate: '2026-03-09 10:00:00',
        endDate: '2026-03-19',
        initialKm: 1000,
        finalKm: 1500,
    );
})->throws(
    RentalDomainException::class,
    RentalError::INVALID_DATE_FORMAT->message(),
    RentalError::INVALID_DATE_FORMAT->value
);

it('throws RentalDomainException when date is semantically invalid', function () {
    Rental::new(
        carId: 1,
        carUuid: (string) Str::uuid(),
        clientId: 1,
        clientUuid: (string) Str::uuid(),
        dayPriceCents: 5000,
        startDate: '2026-02-30 10:00:00',
        endDate: '2026-03-19 10:00:00',
        initialKm: 1000,
        finalKm: 1500,
    );
})->throws(
    RentalDomainException::class,
    RentalError::INVALID_DATE_FORMAT->message(),
    RentalError::INVALID_DATE_FORMAT->value
);

it('throws RentalDomainException when end date is before start date', function () {
    Rental::new(
        carId: 1,
        carUuid: (string) Str::uuid(),
        clientId: 1,
        clientUuid: (string) Str::uuid(),
        dayPriceCents: 5000,
        startDate: '2026-03-19 10:00:00',
        endDate: '2026-03-09 10:00:00',
        initialKm: 1000,
        finalKm: 1500,
    );
})->throws(
    RentalDomainException::class,
    RentalError::INVALID_DATE_INTERVAL->message(),
    RentalError::INVALID_DATE_INTERVAL->value
);

it('allows same date for start and end', function () {
    $rental = Rental::new(
        carId: 1,
        carUuid: (string) Str::uuid(),
        clientId: 1,
        clientUuid: (string) Str::uuid(),
        dayPriceCents: 5000,
        startDate: '2026-03-09 10:00:00',
        endDate: '2026-03-09 10:00:00',
        initialKm: 1000,
        finalKm: 1500,
    );

    expect($rental->startDate)->toBe('2026-03-09 10:00:00')
        ->and($rental->endDate)->toBe('2026-03-09 10:00:00');
});

it('throws RentalDomainException when day price is negative', function () {
    Rental::new(
        carId: 1,
        carUuid: (string) Str::uuid(),
        clientId: 1,
        clientUuid: (string) Str::uuid(),
        dayPriceCents: -1,
        startDate: '2026-03-09 10:00:00',
        endDate: '2026-03-10 10:00:00',
        initialKm: 1000,
        finalKm: 1500,
    );
})->throws(
    RentalDomainException::class,
    RentalError::INVALID_DAY_PRICE->message(),
    RentalError::INVALID_DAY_PRICE->value
);

it('throws RentalDomainException when initial km is negative', function () {
    Rental::new(
        carId: 1,
        carUuid: (string) Str::uuid(),
        clientId: 1,
        clientUuid: (string) Str::uuid(),
        dayPriceCents: 5000,
        startDate: '2026-03-09 10:00:00',
        endDate: '2026-03-10 10:00:00',
        initialKm: -10,
        finalKm: 1500,
    );
})->throws(
    RentalDomainException::class,
    RentalError::INVALID_INITIAL_KM->message(),
    RentalError::INVALID_INITIAL_KM->value
);

it('throws RentalDomainException when final km is negative', function () {
    Rental::new(
        carId: 1,
        carUuid: (string) Str::uuid(),
        clientId: 1,
        clientUuid: (string) Str::uuid(),
        dayPriceCents: 5000,
        startDate: '2026-03-09 10:00:00',
        endDate: '2026-03-10 10:00:00',
        initialKm: 1000,
        finalKm: -1,
    );
})->throws(
    RentalDomainException::class,
    RentalError::INVALID_FINAL_KM->message(),
    RentalError::INVALID_FINAL_KM->value
);

it('throws RentalDomainException when final km is less than initial km', function () {
    Rental::new(
        carId: 1,
        carUuid: (string) Str::uuid(),
        clientId: 1,
        clientUuid: (string) Str::uuid(),
        dayPriceCents: 5000,
        startDate: '2026-03-09 10:00:00',
        endDate: '2026-03-10 10:00:00',
        initialKm: 1500,
        finalKm: 1000,
    );
})->throws(
    RentalDomainException::class,
    RentalError::FINAL_KM_LESS_THAN_INITIAL->message(),
    RentalError::FINAL_KM_LESS_THAN_INITIAL->value
);

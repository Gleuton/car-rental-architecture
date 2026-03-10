<?php

declare(strict_types=1);

use App\Core\Rental\Domain\Entity\Rental;
use App\Core\Rental\Domain\Errors\RentalError;
use App\Core\Rental\Domain\Exceptions\RentalDomainException;

it('can create a Rental instance', function () {
    $rental = Rental::new(
        carId: 1,
        clientId: 1,
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
        ->and($rental->id)->toBeNull();
});

it('can restore a Rental instance with ID', function () {
    $rental = Rental::restore(
        id: 1,
        carId: 1,
        clientId: 1,
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
        ->and($rental->finalKm)->toBe(1500);
});

it('throws RentalDomainException when start date has invalid format', function () {
    Rental::new(
        carId: 1,
        clientId: 1,
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
        clientId: 1,
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

it('throws RentalDomainException when end date is before start date', function () {
    Rental::new(
        carId: 1,
        clientId: 1,
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
        clientId: 1,
        dayPriceCents: 5000,
        startDate: '2026-03-09 10:00:00',
        endDate: '2026-03-09 10:00:00',
        initialKm: 1000,
        finalKm: 1500,
    );

    expect($rental->startDate)->toBe('2026-03-09 10:00:00')
        ->and($rental->endDate)->toBe('2026-03-09 10:00:00');
});

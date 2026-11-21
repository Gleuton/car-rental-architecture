<?php

declare(strict_types=1);

use App\Core\Rental\Domain\Entity\Rental;
use App\Core\Rental\Domain\Entity\RentalCollection;

beforeEach(function () {
    $this->makeRental = static fn (string $uuid): Rental => Rental::restore(
        carUuid: '44444444-4444-4444-8444-444444444444',
        clientUuid: '55555555-5555-4555-8555-555555555555',
        dayPriceCents: 5000,
        startDate: '2026-03-09 10:00:00',
        endDate: '2026-03-19 10:00:00',
        initialKm: 1000,
        finalKm: 1500,
        uuid: $uuid,
    );

    $this->firstRental = ($this->makeRental)('11111111-1111-4111-8111-111111111111');
    $this->secondRental = ($this->makeRental)('22222222-2222-4222-8222-222222222222');
});

it('creates a collection with the given rentals in order', function () {
    $collection = new RentalCollection([$this->firstRental, $this->secondRental]);

    expect($collection)->toHaveCount(2)
        ->and($collection->all())->toBe([$this->firstRental, $this->secondRental]);
});

it('creates an empty collection by default', function () {
    $collection = new RentalCollection();

    expect($collection->isEmpty())->toBeTrue()
        ->and($collection)->toHaveCount(0)
        ->and($collection->all())->toBe([]);
});

it('is not empty when it has rentals', function () {
    $collection = new RentalCollection([$this->firstRental]);

    expect($collection->isEmpty())->toBeFalse();
});

it('appends a rental and returns the same collection', function () {
    $collection = new RentalCollection([$this->firstRental]);

    $result = $collection->add($this->secondRental);

    expect($result)->toBe($collection)
        ->and($collection->all())->toBe([$this->firstRental, $this->secondRental]);
});

it('rejects a non-rental item in the constructor', function () {
    new RentalCollection([$this->firstRental, 'not a rental']);
})->throws(InvalidArgumentException::class, 'A RentalCollection so aceita instancias de '.Rental::class);

it('rejects a non-rental item when adding', function () {
    (new RentalCollection())->add(new stdClass());
})->throws(InvalidArgumentException::class, 'A RentalCollection so aceita instancias de '.Rental::class);

it('iterates over the rentals in order', function () {
    $collection = new RentalCollection([$this->firstRental, $this->secondRental]);

    expect(iterator_to_array($collection))->toBe([$this->firstRental, $this->secondRental]);
});

it('serializes to the list of rentals', function () {
    $collection = new RentalCollection([$this->firstRental, $this->secondRental]);

    expect($collection->jsonSerialize())->toBe([$this->firstRental, $this->secondRental]);
});

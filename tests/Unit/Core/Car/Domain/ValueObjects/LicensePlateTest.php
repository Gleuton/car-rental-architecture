<?php

declare(strict_types=1);

use App\Core\Car\Domain\Exceptions\CarDomainException;
use App\Core\Car\Domain\ValueObjects\LicensePlate;

it('accepts a license plate within the length limits', function (string $plate) {
    $licensePlate = new LicensePlate($plate);

    expect($licensePlate->value)->toBe($plate);
})->with([
    'minimum length (7)' => 'ABC1D23',
    'formatted plate (8)' => 'ABC-1234',
    'maximum length (10)' => 'ABCD-12345',
    'surrounded by spaces' => '  ABC1D23  ',
]);

it('rejects an empty license plate', function (string $plate) {
    new LicensePlate($plate);
})->with([
    'empty' => '',
    'only spaces' => '   ',
])->throws(CarDomainException::class, 'License plate cannot be empty');

it('rejects a license plate shorter than 7 characters', function (string $plate) {
    new LicensePlate($plate);
})->with([
    'one below minimum (6)' => 'ABC123',
    'spaces do not count' => '  ABC123  ',
])->throws(CarDomainException::class, 'License plate must have at least 7 characters');

it('rejects a license plate longer than 10 characters', function () {
    new LicensePlate('ABCD-123456');
})->throws(CarDomainException::class, 'License plate cannot exceed 10 characters');

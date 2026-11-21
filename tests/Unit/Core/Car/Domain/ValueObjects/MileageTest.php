<?php

declare(strict_types=1);

use App\Core\Car\Domain\Exceptions\CarDomainException;
use App\Core\Car\Domain\ValueObjects\Mileage;

it('accepts zero or positive mileage', function (int $km) {
    $mileage = new Mileage($km);

    expect($mileage->mileage)->toBe($km);
})->with([
    'zero' => 0,
    'one' => 1,
    'high mileage' => 250_000,
]);

it('rejects negative mileage', function (int $km) {
    new Mileage($km);
})->with([
    'minus one' => -1,
    'large negative' => -100_000,
])->throws(CarDomainException::class, 'Kilometers must be zero or positive');

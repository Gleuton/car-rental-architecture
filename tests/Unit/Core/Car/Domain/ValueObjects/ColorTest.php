<?php

declare(strict_types=1);

use App\Core\Car\Domain\Exceptions\CarDomainException;
use App\Core\Car\Domain\ValueObjects\Color;

it('accepts a color within the length limits', function (string $value) {
    $color = new Color($value);

    expect($color->value)->toBe($value);
})->with([
    'minimum length (3)' => 'Red',
    'common color' => 'Prata',
    'maximum length (50)' => str_repeat('a', 50),
    'multibyte at maximum length (50)' => str_repeat('é', 50),
    'surrounded by spaces' => '  Azul  ',
]);

it('rejects an empty color', function (string $value) {
    new Color($value);
})->with([
    'empty' => '',
    'only spaces' => '   ',
])->throws(CarDomainException::class, 'Color cannot be empty');

it('rejects a color shorter than 3 characters', function (string $value) {
    new Color($value);
})->with([
    'one below minimum (2)' => 'Az',
    'spaces do not count' => '  Az  ',
])->throws(CarDomainException::class, 'Color must have at least 3 characters');

it('rejects a color longer than 50 characters', function (string $value) {
    new Color($value);
})->with([
    'one above maximum (51)' => str_repeat('a', 51),
    'multibyte above maximum (51)' => str_repeat('é', 51),
])->throws(CarDomainException::class, 'Color cannot exceed 50 characters');

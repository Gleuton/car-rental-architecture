<?php

declare(strict_types=1);

use App\Core\Brand\Domain\Entity\Brand;
use App\Core\Brand\Domain\Entity\BrandCollection;

it('can create a BrandCollection with valid items', function () {
    $brands = [
        Brand::new('Fiat', 'fiat.png'),
        Brand::new('BMW', 'BMW.png'),
    ];

    $collection = new BrandCollection($brands);

    expect($collection)->toHaveCount(2)
        ->and($collection->all()[0])->toBe($brands[0]);
});

it('throws exception when adding invalid item to BrandCollection in constructor', function () {
    new BrandCollection(['not a brand']);
})->throws(InvalidArgumentException::class, 'A BrandCollection só aceita instâncias de App\\Core\\Brand\\Domain\\Entity\\Brand.');

it('throws exception when using add with invalid item', function () {
    $collection = new BrandCollection;
    $collection->add('invalid');
})->throws(InvalidArgumentException::class, 'A BrandCollection só aceita instâncias de App\\Core\\Brand\\Domain\\Entity\\Brand.');

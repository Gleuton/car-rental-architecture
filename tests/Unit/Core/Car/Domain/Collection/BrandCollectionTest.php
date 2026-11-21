<?php

declare(strict_types=1);

use App\Core\Car\Domain\Collection\BrandCollection;
use App\Core\Car\Domain\Entity\Brand;

const TEST_FIAT_BRAND_UUID = 'bbbbbbbb-bbbb-4bbb-8bbb-bbbbbbbbbbbb';
const TEST_BMW_BRAND_UUID = 'cccccccc-cccc-4ccc-8ccc-cccccccccccc';

it('can create a BrandCollection with valid items', function () {
    $brands = [
        Brand::restore('Fiat', 'fiat.png', TEST_FIAT_BRAND_UUID),
        Brand::restore('BMW', 'BMW.png', TEST_BMW_BRAND_UUID),
    ];

    $collection = new BrandCollection($brands);

    expect($collection)->toHaveCount(2)
        ->and($collection->all()[0])->toBe($brands[0]);
});

it('throws exception when adding invalid item to BrandCollection in constructor', function () {
    new BrandCollection(['not a brand']);
})->throws(InvalidArgumentException::class, 'A BrandCollection só aceita instâncias de '.Brand::class);

it('throws exception when using add with invalid item', function () {
    $collection = new BrandCollection();
    $collection->add('invalid');
})->throws(InvalidArgumentException::class, 'A BrandCollection só aceita instâncias de '.Brand::class);

it('can add a valid Brand to BrandCollection', function () {
    $collection = new BrandCollection();
    $brand = Brand::restore('Fiat', 'fiat.png', TEST_FIAT_BRAND_UUID);
    $collection->add($brand);

    expect($collection)->toHaveCount(1)
        ->and($collection->all()[0])->toBe($brand);
});

it('returns true for isEmpty when BrandCollection is empty', function () {
    $collection = new BrandCollection();

    expect($collection->isEmpty())->toBeTrue();
});

it('returns false for isEmpty when BrandCollection has items', function () {
    $brand = Brand::restore('Fiat', 'fiat.png', TEST_FIAT_BRAND_UUID);
    $collection = new BrandCollection([$brand]);

    expect($collection->isEmpty())->toBeFalse();
});

it('can iterate over BrandCollection', function () {
    $brands = [
        Brand::restore('Fiat', 'fiat.png', TEST_FIAT_BRAND_UUID),
        Brand::restore('BMW', 'bmw.png', TEST_BMW_BRAND_UUID),
    ];
    $collection = new BrandCollection($brands);

    $items = [];
    foreach ($collection as $brand) {
        $items[] = $brand;
    }

    expect($items)->toHaveCount(2)
        ->and($items[0])->toBe($brands[0])
        ->and($items[1])->toBe($brands[1]);
});

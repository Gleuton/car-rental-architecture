<?php

declare(strict_types=1);

use App\Core\Car\Domain\Entity\Brand;
use App\Core\Car\Domain\Exceptions\BrandDomainException;
use App\Core\Shared\Domain\Errors\SharedError;
use App\Core\Shared\Domain\Exceptions\SharedDomainException;
use App\Core\Shared\Domain\ValueObjects\Uuid;

const TEST_BRAND_UUID = '11111111-1111-4111-8111-111111111111';

it('can create a Brand instance with the given uuid', function () {
    $brand = Brand::new(Uuid::fromString(TEST_BRAND_UUID), 'Fiat', 'fiat.png');

    expect($brand->name())->toBe('Fiat')
        ->and($brand->imagePath())->toBe('fiat.png')
        ->and($brand->uuid())->toBe(TEST_BRAND_UUID);
});

it('can restore a Brand instance with uuid', function () {
    $brand = Brand::restore('Fiat', 'fiat.png', TEST_BRAND_UUID);

    expect($brand->uuid())->toBe(TEST_BRAND_UUID)
        ->and($brand->name())->toBe('Fiat')
        ->and($brand->imagePath())->toBe('fiat.png');
});

it('throws exception when restoring a Brand with an invalid uuid', function () {
    Brand::restore('Fiat', 'fiat.png', 'not-a-uuid');
})->throws(SharedDomainException::class, SharedError::INVALID_UUID->message());

it('throws exception when creating a Brand instance with empty name', function () {
    Brand::new(Uuid::fromString(TEST_BRAND_UUID), '', 'fiat.png');
})->throws(BrandDomainException::class, 'Brand name cannot be empty');

it('throws exception when creating a Brand instance with name shorter than 3 characters', function () {
    Brand::new(Uuid::fromString(TEST_BRAND_UUID), 'Fi', 'fiat.png');
})->throws(BrandDomainException::class, 'Brand name must have at least 3 characters');

it('throws exception when creating a Brand instance with name longer than 120 characters', function () {
    Brand::new(Uuid::fromString(TEST_BRAND_UUID), str_repeat('x', 121), 'fiat.png');
})->throws(BrandDomainException::class, 'Brand name too long');

it('throws exception when creating a Brand with whitespace-only name', function () {
    Brand::new(Uuid::fromString(TEST_BRAND_UUID), '   ', 'fiat.png');
})->throws(BrandDomainException::class, 'Brand name cannot be empty');

it('can create a Brand with name exactly 3 characters', function () {
    $brand = Brand::new(Uuid::fromString(TEST_BRAND_UUID), 'BMW', 'bmw.png');

    expect($brand->name())->toBe('BMW');
});

it('can create a Brand with name exactly 120 characters', function () {
    $name = str_repeat('x', 120);
    $brand = Brand::new(Uuid::fromString(TEST_BRAND_UUID), $name, 'brand.png');

    expect($brand->name())->toBe($name);
});

it('can update a Brand name keeping the image', function () {
    $brand = Brand::restore('Fiat', 'fiat.png', TEST_BRAND_UUID);
    $updated = $brand->rename('Toyota');

    expect($updated->name())->toBe('Toyota')
        ->and($updated->imagePath())->toBe('fiat.png')
        ->and($updated->uuid())->toBe(TEST_BRAND_UUID);
});

it('can update a Brand image keeping the name', function () {
    $brand = Brand::restore('Fiat', 'fiat.png', TEST_BRAND_UUID);
    $updated = $brand->changeLogo('fiat_new.png');

    expect($updated->name())->toBe('Fiat')
        ->and($updated->imagePath())->toBe('fiat_new.png')
        ->and($updated->uuid())->toBe(TEST_BRAND_UUID);
});

it('can update a Brand name and image', function () {
    $brand = Brand::restore('Fiat', 'fiat.png', TEST_BRAND_UUID);
    $updated = $brand->rename('Toyota')
        ->changeLogo('toyota.png');

    expect($updated->name())->toBe('Toyota')
        ->and($updated->imagePath())->toBe('toyota.png')
        ->and($updated->uuid())->toBe(TEST_BRAND_UUID);
});

it('throws exception when updating a Brand with invalid name', function () {
    $brand = Brand::restore('Fiat', 'fiat.png', TEST_BRAND_UUID);
    $brand->rename(name: 'Fi');
})->throws(BrandDomainException::class, 'Brand name must have at least 3 characters');

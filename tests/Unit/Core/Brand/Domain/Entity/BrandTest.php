<?php

declare(strict_types=1);

use App\Core\Car\Domain\Entities\Brand;
use App\Core\Car\Domain\Exceptions\BrandDomainException;
use Illuminate\Support\Str;

it('can create a Brand instance', function () {
    $brand = Brand::create('Fiat', 'fiat.png');

    expect($brand->name())->toBe('Fiat')
        ->and($brand->imagePath())->toBe('fiat.png')
        ->and(Str::isUuid($brand->uuid()))->toBeTrue();
});

it('can create a Brand instance with uuid', function () {
    $brandUuid = '11111111-1111-4111-8111-111111111111';
    $brand = Brand::create('Fiat', 'fiat.png', $brandUuid);

    expect($brand->uuid())->toBe($brandUuid)
        ->and($brand->name())->toBe('Fiat')
        ->and($brand->imagePath())->toBe('fiat.png')
        ->and(Str::isUuid($brand->uuid()))->toBeTrue();
});

it('throws exception when creating a Brand instance with empty name', function () {
    Brand::create('', 'fiat.png');
})->throws(BrandDomainException::class, 'Brand name cannot be empty');

it('throws exception when creating a Brand instance with name shorter than 3 characters', function () {
    Brand::create('Fi', 'fiat.png');
})->throws(BrandDomainException::class, 'Brand name must have at least 3 characters');

it('throws exception when creating a Brand instance with name longer than 120 characters', function () {
    Brand::create(str_repeat('x', 121), 'fiat.png');
})->throws(BrandDomainException::class, 'Brand name too long');

it('throws exception when creating a Brand with whitespace-only name', function () {
    Brand::create('   ', 'fiat.png');
})->throws(BrandDomainException::class, 'Brand name cannot be empty');

it('can create a Brand with name exactly 3 characters', function () {
    $brand = Brand::create('BMW', 'bmw.png');

    expect($brand->name())->toBe('BMW');
});

it('can create a Brand with name exactly 120 characters', function () {
    $name = str_repeat('x', 120);
    $brand = Brand::create($name, 'brand.png');

    expect($brand->name())->toBe($name);
});

it('can update a Brand name keeping the image', function () {
    $brand = Brand::create('Fiat', 'fiat.png', '11111111-1111-4111-8111-111111111111');
    $updated = $brand->rename('Toyota');

    expect($updated->name())->toBe('Toyota')
        ->and($updated->imagePath())->toBe('fiat.png')
        ->and($updated->uuid())->toBe($brand->uuid());
});

it('can update a Brand image keeping the name', function () {
    $brand = Brand::create('Fiat', 'fiat.png', '11111111-1111-4111-8111-111111111111');
    $updated = $brand->changeLogo('fiat_new.png');

    expect($updated->name())->toBe('Fiat')
        ->and($updated->imagePath())->toBe('fiat_new.png')
        ->and($updated->uuid())->toBe($brand->uuid());
});

it('can update a Brand name and image', function () {
    $brand = Brand::create('Fiat', 'fiat.png', '11111111-1111-4111-8111-111111111111');
    $updated = $brand->rename('Toyota')
        ->changeLogo('toyota.png');

    expect($updated->name())->toBe('Toyota')
        ->and($updated->imagePath())->toBe('toyota.png')
        ->and($updated->uuid())->toBe($brand->uuid());
});

it('throws exception when updating a Brand with invalid name', function () {
    $brand = Brand::create('Fiat', 'fiat.png', '11111111-1111-4111-8111-111111111111');
    $brand->rename(name: 'Fi');
})->throws(BrandDomainException::class, 'Brand name must have at least 3 characters');

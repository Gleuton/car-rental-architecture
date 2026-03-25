<?php

declare(strict_types=1);

use App\Core\Brand\Domain\Entity\Brand;
use App\Core\Brand\Domain\Exceptions\BrandDomainException;

it('can create a Brand instance', function () {
    $brand = Brand::new('Fiat', 'fiat.png');

    expect($brand->name->value)->toBe('Fiat')
        ->and($brand->image->path)->toBe('fiat.png')
        ->and($brand->id)->toBeNull();
});

it('can create a Brand instance with ID', function () {
    $brand = Brand::restore(1, 'Fiat', 'fiat.png');

    expect($brand->id)->toBe(1)
        ->and($brand->name->value)->toBe('Fiat')
        ->and($brand->image->path)->toBe('fiat.png');
});

it('throws exception when creating a Brand instance with empty name', function () {
    Brand::new('', 'fiat.png');
})->throws(BrandDomainException::class, 'Brand name cannot be empty');

it('throws exception when creating a Brand instance with name shorter than 3 characters', function () {
    Brand::new('Fi', 'fiat.png');
})->throws(BrandDomainException::class, 'Brand name must have at least 3 characters');

it('throws exception when creating a Brand instance with name longer than 120 characters', function () {
    Brand::new(str_repeat('x', 121), 'fiat.png');
})->throws(BrandDomainException::class, 'Brand name too long');

it('throws exception when creating a Brand with whitespace-only name', function () {
    Brand::new('   ', 'fiat.png');
})->throws(BrandDomainException::class, 'Brand name cannot be empty');

it('can create a Brand with name exactly 3 characters', function () {
    $brand = Brand::new('BMW', 'bmw.png');

    expect($brand->name->value)->toBe('BMW');
});

it('can create a Brand with name exactly 120 characters', function () {
    $name = str_repeat('x', 120);
    $brand = Brand::new($name, 'brand.png');

    expect($brand->name->value)->toBe($name);
});

it('can update a Brand name keeping the image', function () {
    $brand = Brand::restore(1, 'Fiat', 'fiat.png');
    $updated = $brand->update(name: 'Toyota');

    expect($updated->id)->toBe(1)
        ->and($updated->name->value)->toBe('Toyota')
        ->and($updated->image->path)->toBe('fiat.png');
});

it('can update a Brand image keeping the name', function () {
    $brand = Brand::restore(1, 'Fiat', 'fiat.png');
    $updated = $brand->update(image: 'fiat_new.png');

    expect($updated->id)->toBe(1)
        ->and($updated->name->value)->toBe('Fiat')
        ->and($updated->image->path)->toBe('fiat_new.png');
});

it('can update a Brand name and image', function () {
    $brand = Brand::restore(1, 'Fiat', 'fiat.png');
    $updated = $brand->update(name: 'Toyota', image: 'toyota.png');

    expect($updated->id)->toBe(1)
        ->and($updated->name->value)->toBe('Toyota')
        ->and($updated->image->path)->toBe('toyota.png');
});

it('throws exception when updating a Brand with invalid name', function () {
    $brand = Brand::restore(1, 'Fiat', 'fiat.png');
    $brand->update(name: 'Fi');
})->throws(BrandDomainException::class, 'Brand name must have at least 3 characters');

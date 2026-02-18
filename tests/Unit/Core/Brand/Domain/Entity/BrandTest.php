<?php

declare(strict_types=1);

use App\Core\Brand\Domain\Entity\Brand;
use App\Core\Brand\Domain\Exceptions\BrandDomainException;

it('can create a Brand instance', function () {
    $brand = Brand::new('Fiat', 'fiat.png');

    expect($brand->name)->toBe('Fiat')
        ->and($brand->image)->toBe('fiat.png')
        ->and($brand->id)->toBeNull();
});

it('can create a Brand instance with ID', function () {
    $brand = Brand::restore(1, 'Fiat', 'fiat.png');

    expect($brand->id)->toBe(1)
        ->and($brand->name)->toBe('Fiat')
        ->and($brand->image)->toBe('fiat.png');
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

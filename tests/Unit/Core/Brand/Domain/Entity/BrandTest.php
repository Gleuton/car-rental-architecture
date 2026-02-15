<?php

use App\Core\Brand\Domain\Entity\Brand;
use App\Core\Brand\Domain\Exceptions\BrandDomainException;

it('pode criar uma instância de Brand', function () {
    $brand = Brand::new('Fiat', 'fiat.png');

    expect($brand->name)->toBe('Fiat')
        ->and($brand->image)->toBe('fiat.png')
        ->and($brand->id)->toBeNull();
});

it('pode criar uma instância de Brand com ID', function () {
    $brand = Brand::restore(1, 'Fiat', 'fiat.png');

    expect($brand->id)->toBe(1)
        ->and($brand->name)->toBe('Fiat')
        ->and($brand->image)->toBe('fiat.png');
});

it('lança exceção ao criar uma instância de Brand com nome vazio', function () {
    Brand::new('', 'fiat.png');
})->throws(BrandDomainException::class, 'Brand name cannot be empty');

it('lança exceção ao criar uma instância de Brand com nome menor que 3 caracteres', function () {
    Brand::new('Fi', 'fiat.png');
})->throws(BrandDomainException::class, 'Brand name must have at least 3 characters');


it('lança exceção ao criar uma instância de Brand com nome maior que 120 caracteres', function () {
    Brand::new(str_repeat('x', 121), 'fiat.png');
})->throws(BrandDomainException::class, 'Brand name too long');

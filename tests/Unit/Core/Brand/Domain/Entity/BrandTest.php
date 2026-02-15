<?php

use App\Core\Brand\Domain\Entity\Brand;

it('pode criar uma instância de Brand', function () {
    $brand = Brand::create('Fiat', 'fiat.png');

    expect($brand->name)->toBe('Fiat')
        ->and($brand->image)->toBe('fiat.png')
        ->and($brand->id)->toBeNull();
});

it('pode criar uma instância de Brand com ID', function () {
    $brand = Brand::createWithId(1, 'Fiat', 'fiat.png');

    expect($brand->id)->toBe(1)
        ->and($brand->name)->toBe('Fiat')
        ->and($brand->image)->toBe('fiat.png');
});

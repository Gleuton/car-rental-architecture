<?php

use App\Core\Brand\Domain\Entity\Brand;
use App\Core\Brand\Domain\Entity\BrandCollection;

it('pode criar uma BrandCollection com itens válidos', function () {
    $brands = [
        Brand::create('Fiat', 'fiat.png'),
        Brand::create('VW', 'vw.png'),
    ];

    $collection = new BrandCollection($brands);

    expect($collection)->toHaveCount(2)
        ->and($collection->first())->toBe($brands[0]);
});

it('lança exceção ao adicionar item inválido na BrandCollection no construtor', function () {
    new BrandCollection(['not a brand']);
})->throws(InvalidArgumentException::class, 'A BrandCollection só aceita instâncias de App\Core\Brand\Domain\Entity\Brand.');

it('lança exceção ao usar push com item inválido', function () {
    $collection = new BrandCollection();
    $collection->push('invalid');
})->throws(InvalidArgumentException::class, 'A BrandCollection só aceita instâncias de App\Core\Brand\Domain\Entity\Brand.');

it('lança exceção ao usar add com item inválido', function () {
    $collection = new BrandCollection();
    $collection->add('invalid');
})->throws(InvalidArgumentException::class, 'A BrandCollection só aceita instâncias de App\Core\Brand\Domain\Entity\Brand.');

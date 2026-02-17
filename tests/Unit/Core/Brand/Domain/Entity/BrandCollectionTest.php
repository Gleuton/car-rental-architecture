<?php

declare(strict_types=1);

use App\Core\Brand\Domain\Entity\Brand;
use App\Core\Brand\Domain\Entity\BrandCollection;

it('pode criar uma BrandCollection com itens válidos', function () {
    $brands = [
        Brand::new('Fiat', 'fiat.png'),
        Brand::new('BMW', 'BMW.png'),
    ];

    $collection = new BrandCollection($brands);

    expect($collection)->toHaveCount(2)
        ->and($collection->all()[0])->toBe($brands[0]);
});

it('lança exceção ao adicionar item inválido na BrandCollection no construtor', function () {
    new BrandCollection(['not a brand']);
})->throws(InvalidArgumentException::class, 'A BrandCollection só aceita instâncias de App\Core\Brand\Domain\Entity\Brand.');

it('lança exceção ao usar add com item inválido', function () {
    $collection = new BrandCollection;
    $collection->add('invalid');
})->throws(InvalidArgumentException::class, 'A BrandCollection só aceita instâncias de App\Core\Brand\Domain\Entity\Brand.');

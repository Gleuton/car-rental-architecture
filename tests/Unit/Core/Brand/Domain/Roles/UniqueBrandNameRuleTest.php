<?php

declare(strict_types=1);

use App\Core\Brand\Domain\Exceptions\BrandDomainException;
use App\Core\Brand\Domain\Repositories\BrandRepositoryInterface;
use App\Core\Brand\Domain\Roles\UniqueBrandNameRule;

it('valida com sucesso quando o nome da marca é único', function () {
    $repository = Mockery::mock(BrandRepositoryInterface::class);
    $repository->shouldReceive('existsByName')
        ->with('Fiat')
        ->once()
        ->andReturn(false);

    $rule = new UniqueBrandNameRule($repository);

    $rule->validate('Fiat');

    expect(true)->toBeTrue();
});

it('lança exceção quando o nome da marca já existe', function () {
    $repository = Mockery::mock(BrandRepositoryInterface::class);
    $repository->shouldReceive('existsByName')
        ->with('Fiat')
        ->once()
        ->andReturn(true);

    $rule = new UniqueBrandNameRule($repository);

    $rule->validate('Fiat');
})->throws(BrandDomainException::class, 'Brand already exists');

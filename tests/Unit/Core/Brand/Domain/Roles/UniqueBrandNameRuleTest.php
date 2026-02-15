<?php

use App\Core\Brand\Domain\Roles\UniqueBrandNameRule;
use App\Core\Brand\Domain\Repositories\BrandRepositoryInterface;
use App\Core\Brand\Domain\Exceptions\BrandDomainException;

it('valida com sucesso quando o nome da marca é único', function () {
    $repository = Mockery::mock(BrandRepositoryInterface::class);
    $repository->shouldReceive('existsByName')
        ->with('Fiat')
        ->once()
        ->andReturn(false);

    $rule = new UniqueBrandNameRule($repository);
    
    $rule->validate('Fiat');
    
    expect(true)->toBeTrue(); // Se não lançou exceção, passou
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

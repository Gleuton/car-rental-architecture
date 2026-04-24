<?php

declare(strict_types=1);

use App\Core\Brand\Domain\Roles\UniqueBrandNameRule;
use App\Core\Car\Domain\Exceptions\BrandDomainException;
use App\Core\Car\Domain\Repositories\BrandRepositoryInterface;

it('validates successfully when the brand name is unique', function () {
    $repository = Mockery::mock(BrandRepositoryInterface::class);
    $repository->shouldReceive('existsByName')
        ->with('Fiat')
        ->once()
        ->andReturn(false);

    $rule = new UniqueBrandNameRule($repository);

    $rule->validate('Fiat');

    expect(true)->toBeTrue();
});

it('throws exception when the brand name already exists', function () {
    $repository = Mockery::mock(BrandRepositoryInterface::class);
    $repository->shouldReceive('existsByName')
        ->with('Fiat')
        ->once()
        ->andReturn(true);

    $rule = new UniqueBrandNameRule($repository);

    $rule->validate('Fiat');
})->throws(BrandDomainException::class, 'Brand already exists');

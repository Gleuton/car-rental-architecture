<?php

declare(strict_types=1);

use App\Core\Car\Application\UseCases\Brand\FindBrandByUuidUseCase;
use App\Core\Car\Domain\Entity\Brand;
use App\Core\Car\Domain\Errors\BrandError;
use App\Core\Car\Domain\Exceptions\BrandDomainException;
use App\Core\Car\Domain\Repositories\BrandRepositoryInterface;

beforeEach(function () {
    $this->repository = Mockery::mock(BrandRepositoryInterface::class);
    $this->useCase = new FindBrandByUuidUseCase($this->repository);
});

it('returns the brand found by uuid', function () {
    $uuid = '11111111-1111-4111-8111-111111111111';
    $expectedBrand = Brand::restore('Fiat', 'brands/fiat.png', $uuid);

    $this->repository->shouldReceive('findByUuid')
        ->with($uuid)
        ->once()
        ->andReturn($expectedBrand);

    $result = $this->useCase->execute($uuid);

    expect($result)->toBe($expectedBrand);
});

it('propagates exception when brand is not found', function () {
    $uuid = '99999999-9999-4999-8999-999999999999';

    $this->repository->shouldReceive('findByUuid')
        ->with($uuid)
        ->once()
        ->andThrow(new BrandDomainException(BrandError::NOT_FOUND));

    expect(fn () => $this->useCase->execute($uuid))
        ->toThrow(BrandDomainException::class, 'Brand not found');
});

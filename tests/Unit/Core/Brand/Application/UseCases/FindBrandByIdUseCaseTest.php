<?php

declare(strict_types=1);

use App\Core\Brand\Application\DTOs\BrandIdDTO;
use App\Core\Brand\Application\UseCases\FindBrandByIdUseCase;
use App\Core\Brand\Domain\Entity\Brand;
use App\Core\Brand\Domain\Repositories\BrandRepositoryInterface;

it('finds a brand by ID successfully', function () {
    $dto = BrandIdDTO::fromId(1);

    $repository = Mockery::mock(BrandRepositoryInterface::class);

    $expectedBrand = Brand::restore(1, 'Fiat', 'fiat.png');
    $repository->shouldReceive('findById')
        ->with(1)
        ->once()
        ->andReturn($expectedBrand);

    $useCase = new FindBrandByIdUseCase($repository);
    $result = $useCase->execute($dto);

    expect($result)->toBe($expectedBrand);
});

it('propagates exception when brand is not found', function () {
    $dto = BrandIdDTO::fromId(999);

    $repository = Mockery::mock(BrandRepositoryInterface::class);
    $repository->shouldReceive('findById')
        ->with(999)
        ->once()
        ->andThrow(new RuntimeException('Brand not found'));

    $useCase = new FindBrandByIdUseCase($repository);

    expect(fn () => $useCase->execute($dto))
        ->toThrow(RuntimeException::class);
});

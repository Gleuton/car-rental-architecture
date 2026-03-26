<?php

declare(strict_types=1);

use App\Core\Brand\Application\DTOs\BrandIdDTO;
use App\Core\Brand\Application\Services\BrandLogoService;
use App\Core\Brand\Application\UseCases\DeleteBrandUseCase;
use App\Core\Brand\Domain\Entity\Brand;
use App\Core\Brand\Domain\Repositories\BrandRepositoryInterface;

it('deletes a brand successfully', function () {
    $dto = BrandIdDTO::fromId(1);

    $brand = Brand::restore(1, 'Fiat', 'brands/fiat.png');

    $repository = Mockery::mock(BrandRepositoryInterface::class);
    $repository->shouldReceive('findById')
        ->with(1)
        ->once()
        ->andReturn($brand);

    $repository->shouldReceive('delete')
        ->with(1)
        ->once();

    $logoService = Mockery::mock(BrandLogoService::class);
    $logoService->shouldReceive('delete')
        ->with('brands/fiat.png')
        ->once();

    $useCase = new DeleteBrandUseCase($repository, $logoService);
    $useCase->execute($dto);

    expect(true)->toBeTrue();
});

it('propagates exception when brand is not found during delete', function () {
    $dto = BrandIdDTO::fromId(999);

    $repository = Mockery::mock(BrandRepositoryInterface::class);
    $repository->shouldReceive('findById')
        ->with(999)
        ->once()
        ->andThrow(new RuntimeException('Brand not found'));

    $logoService = Mockery::mock(BrandLogoService::class);

    $useCase = new DeleteBrandUseCase($repository, $logoService);

    expect(fn () => $useCase->execute($dto))
        ->toThrow(RuntimeException::class);
});

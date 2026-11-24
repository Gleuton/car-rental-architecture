<?php

declare(strict_types=1);

use App\Core\Car\Application\Services\BrandLogoService;
use App\Core\Car\Application\UseCases\Brand\DeleteBrandByUuidUseCase;
use App\Core\Car\Domain\Entity\Brand;
use App\Core\Car\Domain\Errors\BrandError;
use App\Core\Car\Domain\Exceptions\BrandDomainException;
use App\Core\Car\Domain\Repositories\BrandRepositoryInterface;

beforeEach(function () {
    $this->brandUuid = '11111111-1111-4111-8111-111111111111';
    $this->repository = Mockery::mock(BrandRepositoryInterface::class);
    $this->logoService = Mockery::mock(BrandLogoService::class);

    $this->useCase = new DeleteBrandByUuidUseCase(
        $this->repository,
        $this->logoService
    );
});

it('deletes the brand logo and then the brand', function () {
    $brand = Brand::restore('Fiat', 'brands/fiat.png', $this->brandUuid);

    $this->repository->shouldReceive('findByUuid')
        ->with($this->brandUuid)
        ->once()
        ->globally()->ordered()
        ->andReturn($brand);

    $this->logoService->shouldReceive('delete')
        ->with('brands/fiat.png')
        ->once()
        ->globally()->ordered();

    $this->repository->shouldReceive('deleteByUuid')
        ->with($this->brandUuid)
        ->once()
        ->globally()->ordered();

    $this->useCase->execute($this->brandUuid);
});

it('does not delete anything when brand is not found', function () {
    $this->repository->shouldReceive('findByUuid')
        ->with($this->brandUuid)
        ->once()
        ->andThrow(new BrandDomainException(BrandError::NOT_FOUND));

    $this->logoService->shouldNotReceive('delete');
    $this->repository->shouldNotReceive('deleteByUuid');

    expect(fn () => $this->useCase->execute($this->brandUuid))
        ->toThrow(BrandDomainException::class, 'Brand not found');
});

it('does not delete the brand when logo deletion fails', function () {
    $brand = Brand::restore('Fiat', 'brands/fiat.png', $this->brandUuid);

    $this->repository->shouldReceive('findByUuid')
        ->with($this->brandUuid)
        ->once()
        ->andReturn($brand);

    $this->logoService->shouldReceive('delete')
        ->with('brands/fiat.png')
        ->once()
        ->andThrow(new RuntimeException('Storage unavailable'));

    $this->repository->shouldNotReceive('deleteByUuid');

    expect(fn () => $this->useCase->execute($this->brandUuid))
        ->toThrow(RuntimeException::class, 'Storage unavailable');
});

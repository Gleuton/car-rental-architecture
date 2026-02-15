<?php

use App\Core\Brand\Application\DTOs\BrandIdDTO;
use App\Core\Brand\Application\UseCases\DeleteBrandUseCase;
use App\Core\Brand\Domain\Entity\Brand;
use App\Core\Brand\Domain\Repositories\BrandRepositoryInterface;
use App\Core\Shared\Domain\Storage\FileStorageInterface;

it('deve deletar uma marca com sucesso', function () {
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

    $storage = Mockery::mock(FileStorageInterface::class);
    $storage->shouldReceive('delete')
        ->with('brands/fiat.png')
        ->once();

    $useCase = new DeleteBrandUseCase($repository, $storage);
    $useCase->execute($dto);

    expect(true)->toBeTrue();
});

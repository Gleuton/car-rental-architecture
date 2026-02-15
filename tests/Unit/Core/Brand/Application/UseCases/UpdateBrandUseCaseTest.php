<?php

use App\Core\Brand\Application\DTOs\UpdateBrandDTO;
use App\Core\Brand\Application\UseCases\UpdateBrandUseCase;
use App\Core\Brand\Domain\Entity\Brand;
use App\Core\Brand\Domain\Repositories\BrandRepositoryInterface;
use App\Core\Shared\Domain\Storage\FileStorageInterface;
use App\Http\Requests\Brand\UpdateBrandRequest;
use Illuminate\Http\UploadedFile;

it('deve atualizar uma marca com sucesso', function () {
    $file = UploadedFile::fake()->create('fiat_updated.png', 100);
    $requestMock = Mockery::mock(UpdateBrandRequest::class);
    $requestMock->name = 'Fiat Updated';
    $requestMock->shouldReceive('file')->with('image')->andReturn($file);

    $dto = UpdateBrandDTO::fromRequestId($requestMock, 1);

    $oldBrand = Brand::restore(1, 'Fiat', 'brands/fiat.png');

    $repository = Mockery::mock(BrandRepositoryInterface::class);
    $repository->shouldReceive('findById')->with(1)->once()->andReturn($oldBrand);

    $storage = Mockery::mock(FileStorageInterface::class);
    $storage->shouldReceive('delete')->with('brands/fiat.png')->once();
    $storage->shouldReceive('upload')->with($file, 'brands')->once()->andReturn('brands/fiat_updated.png');

    $updatedBrand = Brand::restore(1, 'Fiat Updated', 'brands/fiat_updated.png');
    $repository->shouldReceive('update')
        ->with(Mockery::type(Brand::class))
        ->once()
        ->andReturn($updatedBrand);

    $useCase = new UpdateBrandUseCase($repository, $storage);
    $result = $useCase->execute($dto);

    expect($result->name)->toBe('Fiat Updated')
        ->and($result->image)->toBe('brands/fiat_updated.png');
});

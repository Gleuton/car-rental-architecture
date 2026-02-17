<?php

declare(strict_types=1);

use App\Core\Brand\Application\DTOs\UpdateBrandDTO;
use App\Core\Brand\Application\UseCases\UpdateBrandUseCase;
use App\Core\Brand\Domain\Entity\Brand;
use App\Core\Brand\Domain\Repositories\BrandRepositoryInterface;
use App\Core\Shared\Domain\Storage\DomainFile;
use App\Core\Shared\Domain\Storage\FileStorageInterface;
use App\Core\Shared\Domain\Storage\StoredFile;
use App\Http\Requests\Brand\UpdateBrandRequest;
use Illuminate\Http\UploadedFile;

it('deve atualizar uma marca com sucesso', function () {

    $file = UploadedFile::fake()->create('fiat_updated.png', 100);
    $requestMock = Mockery::mock(UpdateBrandRequest::class);

    $requestMock->shouldReceive('file')
        ->with('image')
        ->andReturn($file);

    $requestMock->shouldReceive('input')
        ->with('name')
        ->andReturn('Fiat Updated');

    $dto = UpdateBrandDTO::fromRequestId($requestMock, 1);

    $oldBrand = Brand::restore(1, 'Fiat', 'brands/fiat.png');

    $repository = Mockery::mock(BrandRepositoryInterface::class);
    $repository->shouldReceive('findById')
        ->with(1)
        ->once()
        ->andReturn($oldBrand);

    $storage = Mockery::mock(FileStorageInterface::class);

    $storage->shouldReceive('delete')
        ->with('brands/fiat.png')
        ->once()
        ->andReturn(true);

    $storedFile = new StoredFile('brands/fiat_updated.png', '');

    $storage->shouldReceive('upload')
        ->with(
            Mockery::type(DomainFile::class),
            'brands'
        )
        ->once()
        ->andReturn($storedFile);

    $updatedBrand = Brand::restore(
        1,
        'Fiat Updated',
        'brands/fiat_updated.png'
    );

    $repository->shouldReceive('update')
        ->with(Mockery::type(Brand::class))
        ->once()
        ->andReturn($updatedBrand);

    $useCase = new UpdateBrandUseCase($repository, $storage);

    $result = $useCase->execute($dto);

    expect($result->name)->toBe('Fiat Updated')
        ->and($result->image)->toBe('brands/fiat_updated.png');
});

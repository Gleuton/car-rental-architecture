<?php

declare(strict_types=1);

use App\Core\CarModel\Application\DTOs\CarModelIdDTO;
use App\Core\CarModel\Application\UseCases\DeleteCarModelUseCase;
use App\Core\CarModel\Domain\Entity\CarModel;
use App\Core\CarModel\Domain\Repositories\CarModelRepositoryInterface;
use App\Core\Shared\Domain\Storage\FileStorageInterface;

it('deletes a car model successfully', function () {
    $dto = CarModelIdDTO::fromId(1);

    $carModel = CarModel::restore(
        id: 1,
        brandId: 1,
        name: 'Corolla',
        image: 'car_models/corolla.png',
        doorsNumber: 4,
        seatsNumber: 5,
        airbags: true,
        abs: true
    );

    $repository = Mockery::mock(CarModelRepositoryInterface::class);
    $repository->shouldReceive('findById')
        ->with(1)
        ->once()
        ->andReturn($carModel);

    $repository->shouldReceive('delete')
        ->with(1)
        ->once();

    $storage = Mockery::mock(FileStorageInterface::class);
    $storage->shouldReceive('delete')
        ->with('car_models/corolla.png')
        ->once();

    $useCase = new DeleteCarModelUseCase($repository, $storage);
    $useCase->execute($dto);

    expect(true)->toBeTrue();
});

it('propagates exception when car model is not found during delete', function () {
    $dto = CarModelIdDTO::fromId(999);

    $repository = Mockery::mock(CarModelRepositoryInterface::class);
    $repository->shouldReceive('findById')
        ->with(999)
        ->once()
        ->andThrow(new RuntimeException('Car model not found'));

    $storage = Mockery::mock(FileStorageInterface::class);

    $useCase = new DeleteCarModelUseCase($repository, $storage);

    expect(fn () => $useCase->execute($dto))
        ->toThrow(RuntimeException::class);
});

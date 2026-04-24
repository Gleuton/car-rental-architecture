<?php

declare(strict_types=1);

use App\Core\Car\Application\DTOs\CarModel\CarModelUuidDTO;
use App\Core\Car\Application\UseCases\CarModel\DeleteCarModelUseCase;
use App\Core\Car\Domain\Entities\CarModel;
use App\Core\Car\Domain\Repositories\CarModelRepositoryInterface;
use App\Core\Shared\Domain\Storage\FileStorageInterface;

it('deletes a car model successfully', function () {
    $uuid = '11111111-1111-4111-8111-111111111111';
    $dto = CarModelUuidDTO::fromUuid($uuid);

    $carModel = CarModel::restore(
        brandUuid: '22222222-2222-4222-8222-222222222222',
        name: 'Corolla',
        image: 'car_models/corolla.png',
        doorsNumber: 4,
        seatsNumber: 5,
        airbags: true,
        abs: true,
        uuid: $uuid,
    );

    $repository = Mockery::mock(CarModelRepositoryInterface::class);
    $repository->shouldReceive('findByUuid')
        ->with($uuid)
        ->once()
        ->andReturn($carModel);

    $repository->shouldReceive('deleteByUuid')
        ->with($uuid)
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
    $uuid = '99999999-9999-4999-8999-999999999999';
    $dto = CarModelUuidDTO::fromUuid($uuid);

    $repository = Mockery::mock(CarModelRepositoryInterface::class);
    $repository->shouldReceive('findByUuid')
        ->with($uuid)
        ->once()
        ->andThrow(new RuntimeException('Car model not found'));

    $storage = Mockery::mock(FileStorageInterface::class);

    $useCase = new DeleteCarModelUseCase($repository, $storage);

    expect(fn () => $useCase->execute($dto))
        ->toThrow(RuntimeException::class);
});

<?php

declare(strict_types=1);

use App\Core\CarModel\Application\DTOs\CarModelIdDTO;
use App\Core\CarModel\Application\UseCases\FindCarModelByIdUseCase;
use App\Core\CarModel\Domain\Entity\CarModel;
use App\Core\CarModel\Domain\Repositories\CarModelRepositoryInterface;

it('find Model Car successfully', function () {
    $dto = CarModelIdDTO::fromId(42);
    $repository = Mockery::mock(CarModelRepositoryInterface::class);

    $expectedModel = CarModel::restore(
        42,
        66,
        'Model S',
        'Toyota_Model_S_2020.jpg',
        2,
        2,
        true,
        true
    );

    $repository->shouldReceive('findById')
        ->with(42)
        ->once()
        ->andReturn($expectedModel);

    $useCase = new FindCarModelByIdUseCase($repository);
    $result = $useCase->execute($dto);

    expect($result)->toBe($expectedModel);
});

it('propagates exception when model car is not found', function () {
    $dto = CarModelIdDTO::fromId(999);

    $repository = Mockery::mock(CarModelRepositoryInterface::class);
    $repository->shouldReceive('findById')
        ->with(999)
        ->once()
        ->andThrow(
            new RuntimeException('Car Model not found')
        );

    $useCase = new FindCarModelByIdUseCase($repository);

    expect(fn () => $useCase->execute($dto))
        ->toThrow(RuntimeException::class);
});

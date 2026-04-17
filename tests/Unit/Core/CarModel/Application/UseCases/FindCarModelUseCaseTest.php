<?php

declare(strict_types=1);

use App\Core\CarModel\Application\DTOs\CarModelIdDTO;
use App\Core\CarModel\Application\UseCases\FindCarModelByIdUseCase;
use App\Core\CarModel\Domain\Entity\CarModel;
use App\Core\CarModel\Domain\Repositories\CarModelRepositoryInterface;

it('find Model Car successfully', function () {
    $uuid = '11111111-1111-4111-8111-111111111111';
    $dto = CarModelIdDTO::fromUuid($uuid);
    $repository = Mockery::mock(CarModelRepositoryInterface::class);

    $expectedModel = CarModel::restore(
        42,
        '66666666-6666-4666-8666-666666666666',
        'Model S',
        'Toyota_Model_S_2020.jpg',
        2,
        2,
        true,
        true,
        $uuid,
    );

    $repository->shouldReceive('findByUuid')
        ->with($uuid)
        ->once()
        ->andReturn($expectedModel);

    $useCase = new FindCarModelByIdUseCase($repository);
    $result = $useCase->execute($dto);

    expect($result)->toBe($expectedModel);
});

it('propagates exception when model car is not found', function () {
    $uuid = '99999999-9999-4999-8999-999999999999';
    $dto = CarModelIdDTO::fromUuid($uuid);

    $repository = Mockery::mock(CarModelRepositoryInterface::class);
    $repository->shouldReceive('findByUuid')
        ->with($uuid)
        ->once()
        ->andThrow(
            new RuntimeException('Car Model not found')
        );

    $useCase = new FindCarModelByIdUseCase($repository);

    expect(static fn () => $useCase->execute($dto))
        ->toThrow(RuntimeException::class);
});

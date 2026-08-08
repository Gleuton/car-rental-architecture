<?php

declare(strict_types=1);

use App\Core\Car\Application\DTOs\CarModel\CarModelUuidDTO;
use App\Core\Car\Application\UseCases\CarModel\FindCarModelByUuidUseCase;
use App\Core\Car\Domain\Entity\CarModel;
use App\Core\Car\Domain\Repositories\CarModelRepositoryInterface;

it('find Model Car successfully', function () {
    $uuid = '11111111-1111-4111-8111-111111111111';
    $dto = CarModelUuidDTO::fromUuid($uuid);
    $repository = Mockery::mock(CarModelRepositoryInterface::class);

    $expectedModel = CarModel::restore(
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

    $useCase = new FindCarModelByUuidUseCase($repository);
    $result = $useCase->execute($dto);

    expect($result)->toBe($expectedModel);
});

it('propagates exception when model car is not found', function () {
    $uuid = '99999999-9999-4999-8999-999999999999';
    $dto = CarModelUuidDTO::fromUuid($uuid);

    $repository = Mockery::mock(CarModelRepositoryInterface::class);
    $repository->shouldReceive('findByUuid')
        ->with($uuid)
        ->once()
        ->andThrow(
            new RuntimeException('Car Model not found')
        );

    $useCase = new FindCarModelByUuidUseCase($repository);

    expect(static fn () => $useCase->execute($dto))
        ->toThrow(RuntimeException::class);
});

<?php

declare(strict_types=1);

use App\Core\Car\Application\DTOs\CarIdDTO;
use App\Core\Car\Application\UseCases\FindCarUseCase;
use App\Core\Car\Domain\Entity\Car;
use App\Core\Car\Domain\Repositories\CarRepositoryInterface;

it('finds a car by UUID successfully', function () {
    $carUuid = '11111111-1111-4111-8111-111111111111';
    $dto = CarIdDTO::fromUuid($carUuid);

    $repository = Mockery::mock(CarRepositoryInterface::class);

    $expectedCar = Car::restore(
        1,
        1,
        '22222222-2222-4222-8222-222222222222',
        'ABC-1234',
        'Red',
        true,
        50000,
        $carUuid,
    );

    $repository->shouldReceive('findByUuid')
        ->with($carUuid)
        ->once()
        ->andReturn($expectedCar);

    $useCase = new FindCarUseCase($repository);
    $result = $useCase->execute($dto);

    expect($result)->toBe($expectedCar);
});

it('propagates exception when car is not found', function () {
    $carUuid = '99999999-9999-4999-8999-999999999999';
    $dto = CarIdDTO::fromUuid($carUuid);

    $repository = Mockery::mock(CarRepositoryInterface::class);
    $repository->shouldReceive('findByUuid')
        ->with($carUuid)
        ->once()
        ->andThrow(new RuntimeException('Car not found'));

    $useCase = new FindCarUseCase($repository);

    expect(fn () => $useCase->execute($dto))
        ->toThrow(RuntimeException::class);
});

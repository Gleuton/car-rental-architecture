<?php

declare(strict_types=1);

use App\Core\Car\Application\DTOs\CarIdDTO;
use App\Core\Car\Application\UseCases\FindCarUseCase;
use App\Core\Car\Domain\Entity\Car;
use App\Core\Car\Domain\Repositories\CarRepositoryInterface;

it('finds a car by ID successfully', function () {
    $dto = CarIdDTO::fromId(1);

    $repository = Mockery::mock(CarRepositoryInterface::class);

    $expectedCar = Car::restore(
        1,
        1,
        'ABC-1234',
        'Red',
        true,
        50000
    );

    $repository->shouldReceive('findById')
        ->with(1)
        ->once()
        ->andReturn($expectedCar);

    $useCase = new FindCarUseCase($repository);
    $result = $useCase->execute($dto);

    expect($result)->toBe($expectedCar);
});

it('propagates exception when car is not found', function () {
    $dto = CarIdDTO::fromId(999);

    $repository = Mockery::mock(CarRepositoryInterface::class);
    $repository->shouldReceive('findById')
        ->with(999)
        ->once()
        ->andThrow(new RuntimeException('Car not found'));

    $useCase = new FindCarUseCase($repository);

    expect(fn () => $useCase->execute($dto))
        ->toThrow(RuntimeException::class);
});

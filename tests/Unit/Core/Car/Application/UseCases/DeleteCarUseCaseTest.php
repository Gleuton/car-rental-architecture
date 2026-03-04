<?php

declare(strict_types=1);

use App\Core\Car\Application\DTOs\CarIdDTO;
use App\Core\Car\Application\UseCases\DeleteCarUseCase;
use App\Core\Car\Domain\Repositories\CarRepositoryInterface;

it('deletes a car successfully', function () {
    $dto = CarIdDTO::fromId(1);

    $repository = Mockery::mock(CarRepositoryInterface::class);
    $repository->shouldReceive('delete')
        ->with(1)
        ->once();

    $useCase = new DeleteCarUseCase($repository);
    $useCase->execute($dto);

    expect(true)->toBeTrue();
});

it('propagates exception when car is not found during delete', function () {
    $dto = CarIdDTO::fromId(999);

    $repository = Mockery::mock(CarRepositoryInterface::class);
    $repository->shouldReceive('delete')
        ->with(999)
        ->once()
        ->andThrow(new RuntimeException('Car not found'));

    $useCase = new DeleteCarUseCase($repository);

    expect(fn () => $useCase->execute($dto))
        ->toThrow(RuntimeException::class);
});

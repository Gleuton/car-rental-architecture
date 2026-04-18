<?php

declare(strict_types=1);

use App\Core\Car\Application\DTOs\CarUuidDTO;
use App\Core\Car\Application\UseCases\DeleteCarByUuidUseCase;
use App\Core\Car\Domain\Repositories\CarRepositoryInterface;

it('deletes a car successfully', function () {
    $carUuid = '11111111-1111-4111-8111-111111111111';
    $dto = CarUuidDTO::fromUuid($carUuid);

    $repository = Mockery::mock(CarRepositoryInterface::class);
    $repository->shouldReceive('deleteByUuid')
        ->with($carUuid)
        ->once();

    $useCase = new DeleteCarByUuidUseCase($repository);
    $useCase->execute($dto);

    expect(true)->toBeTrue();
});

it('propagates exception when car is not found during delete', function () {
    $carUuid = '99999999-9999-4999-8999-999999999999';
    $dto = CarUuidDTO::fromUuid($carUuid);

    $repository = Mockery::mock(CarRepositoryInterface::class);
    $repository->shouldReceive('deleteByUuid')
        ->with($carUuid)
        ->once()
        ->andThrow(new RuntimeException('Car not found'));

    $useCase = new DeleteCarByUuidUseCase($repository);

    expect(fn () => $useCase->execute($dto))
        ->toThrow(RuntimeException::class);
});

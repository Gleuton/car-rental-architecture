<?php

declare(strict_types=1);

use App\Core\Rental\Application\DTOs\RentalIdDTO;
use App\Core\Rental\Application\UseCases\DeleteRentalUseCase;
use App\Core\Rental\Domain\Repositories\RentalRepositoryInterface;

it('deletes a rental successfully', function () {
    $dto = RentalIdDTO::fromId(1);

    $repository = Mockery::mock(RentalRepositoryInterface::class);
    $repository->shouldReceive('delete')
        ->with(1)
        ->once();

    $useCase = new DeleteRentalUseCase($repository);
    $useCase->execute($dto);

    expect(true)->toBeTrue();
});

it('propagates exception when rental is not found during delete', function () {
    $dto = RentalIdDTO::fromId(999);

    $repository = Mockery::mock(RentalRepositoryInterface::class);
    $repository->shouldReceive('delete')
        ->with(999)
        ->once()
        ->andThrow(new RuntimeException('Rental not found'));

    $useCase = new DeleteRentalUseCase($repository);

    expect(fn () => $useCase->execute($dto))
        ->toThrow(RuntimeException::class);
});

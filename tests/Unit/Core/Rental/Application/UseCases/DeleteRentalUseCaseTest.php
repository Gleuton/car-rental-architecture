<?php

declare(strict_types=1);

use App\Core\Rental\Application\DTOs\RentalUuidDTO;
use App\Core\Rental\Application\UseCases\DeleteRentalUseCase;
use App\Core\Rental\Domain\Repositories\RentalRepositoryInterface;
use Illuminate\Support\Str;

it('deletes a rental successfully', function () {
    $uuid = (string) Str::uuid();
    $dto = RentalUuidDTO::fromUuid($uuid);

    $repository = Mockery::mock(RentalRepositoryInterface::class);
    $repository->shouldReceive('deleteByUuid')
        ->with($uuid)
        ->once();

    $useCase = new DeleteRentalUseCase($repository);
    $useCase->execute($dto);

    expect(true)->toBeTrue();
});

it('propagates exception when rental is not found during delete', function () {
    $uuid = (string) Str::uuid();
    $dto = RentalUuidDTO::fromUuid($uuid);

    $repository = Mockery::mock(RentalRepositoryInterface::class);
    $repository->shouldReceive('deleteByUuid')
        ->with($uuid)
        ->once()
        ->andThrow(new RuntimeException('Rental not found'));

    $useCase = new DeleteRentalUseCase($repository);

    expect(fn () => $useCase->execute($dto))
        ->toThrow(RuntimeException::class);
});

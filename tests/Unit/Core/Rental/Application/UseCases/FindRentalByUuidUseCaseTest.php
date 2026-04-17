<?php

declare(strict_types=1);

use App\Core\Rental\Application\DTOs\RentalUuidDTO;
use App\Core\Rental\Application\UseCases\FindRentalByUuidUseCase;
use App\Core\Rental\Domain\Entity\Rental;
use App\Core\Rental\Domain\Repositories\RentalRepositoryInterface;
use Illuminate\Support\Str;

it('finds a rental by UUID successfully', function () {
    $uuid = (string) Str::uuid();
    $dto = RentalUuidDTO::fromUuid($uuid);

    $repository = Mockery::mock(RentalRepositoryInterface::class);

    $expectedRental = Rental::restore(
        1,
        1,
        (string) Str::uuid(),
        1,
        (string) Str::uuid(),
        5000,
        '2026-03-01 08:00:00',
        '2026-03-05 08:00:00',
        1000,
        1500,
    );

    $repository->shouldReceive('findByUuid')
        ->with($uuid)
        ->once()
        ->andReturn($expectedRental);

    $useCase = new FindRentalByUuidUseCase($repository);
    $result = $useCase->execute($dto);

    expect($result)->toBe($expectedRental);
});

it('propagates exception when rental is not found', function () {
    $uuid = (string) Str::uuid();
    $dto = RentalUuidDTO::fromUuid($uuid);

    $repository = Mockery::mock(RentalRepositoryInterface::class);
    $repository->shouldReceive('findByUuid')
        ->with($uuid)
        ->once()
        ->andThrow(new RuntimeException('Rental not found'));

    $useCase = new FindRentalByUuidUseCase($repository);

    expect(fn () => $useCase->execute($dto))
        ->toThrow(RuntimeException::class);
});

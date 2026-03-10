<?php

declare(strict_types=1);

use App\Core\Rental\Application\DTOs\UpdateRentalDTO;
use App\Core\Rental\Application\UseCases\UpdateRentalUseCase;
use App\Core\Rental\Domain\Entity\Rental;
use App\Core\Rental\Domain\Repositories\RentalRepositoryInterface;
use App\Http\Requests\Rental\UpdateRentalRequest;

it('updates a rental successfully', function () {
    $request = UpdateRentalRequest::create('/api/rentals/1', 'PUT', [
        'day_price_cents' => 7000,
        'start_date' => '2026-03-10 08:00:00',
        'end_date' => '2026-03-12 08:00:00',
    ]);

    $dto = UpdateRentalDTO::fromRequest($request, 1);

    $existingRental = Rental::restore(
        1,
        1,
        1,
        5000,
        '2026-03-01 08:00:00',
        '2026-03-05 08:00:00',
        1000,
        1500,
    );

    $repository = Mockery::mock(RentalRepositoryInterface::class);
    $repository->shouldReceive('findById')
        ->with(1)
        ->once()
        ->andReturn($existingRental);

    $repository->shouldReceive('update')
        ->once()
        ->andReturnUsing(function (Rental $rental): Rental {
            return $rental;
        });

    $useCase = new UpdateRentalUseCase($repository);
    $result = $useCase->execute($dto);

    expect($result->id)->toBe(1)
        ->and($result->dayPriceCents)->toBe(7000)
        ->and($result->startDate)->toBe('2026-03-10 08:00:00')
        ->and($result->endDate)->toBe('2026-03-12 08:00:00')
        ->and($result->initialKm)->toBe(1000)
        ->and($result->finalKm)->toBe(1500);
});

it('propagates exception when rental is not found during update', function () {
    $request = UpdateRentalRequest::create('/api/rentals/999', 'PUT', [
        'day_price_cents' => 7000,
    ]);

    $dto = UpdateRentalDTO::fromRequest($request, 999);

    $repository = Mockery::mock(RentalRepositoryInterface::class);
    $repository->shouldReceive('findById')
        ->with(999)
        ->once()
        ->andThrow(new RuntimeException('Rental not found'));

    $useCase = new UpdateRentalUseCase($repository);

    expect(static fn () => $useCase->execute($dto))
        ->toThrow(RuntimeException::class);
});

<?php

declare(strict_types=1);

use App\Core\Rental\Application\DTOs\CreateRentalDTO;
use App\Core\Rental\Application\UseCases\CreateRentalUseCase;
use App\Core\Rental\Domain\Entity\Rental;
use App\Core\Rental\Domain\Errors\RentalError;
use App\Core\Rental\Domain\Exceptions\RentalDomainException;
use App\Core\Rental\Domain\Repositories\RentalRepositoryInterface;
use App\Core\Shared\Domain\Identity\UuidGeneratorInterface;
use App\Core\Shared\Domain\ValueObjects\Uuid;
use App\Http\Requests\Rental\StoreRentalRequest;

const TEST_CREATED_RENTAL_UUID = '44444444-4444-4444-8444-444444444444';
const TEST_RENTAL_CAR_UUID = '55555555-5555-4555-8555-555555555555';
const TEST_RENTAL_CLIENT_UUID = '66666666-6666-4666-8666-666666666666';

beforeEach(function () {
    $this->repository = Mockery::mock(RentalRepositoryInterface::class);
    $this->uuidGenerator = Mockery::mock(UuidGeneratorInterface::class);
    $this->uuidGenerator->shouldReceive('generate')
        ->andReturn(Uuid::fromString(TEST_CREATED_RENTAL_UUID));

    $this->useCase = new CreateRentalUseCase($this->repository, $this->uuidGenerator);
});

/**
 * @param  array<string, int|string>  $overrides
 */
function createRentalDTO(array $overrides = []): CreateRentalDTO
{
    $request = StoreRentalRequest::create('/api/rentals', 'POST', array_merge([
        'car_uuid' => TEST_RENTAL_CAR_UUID,
        'client_uuid' => TEST_RENTAL_CLIENT_UUID,
        'day_price_cents' => 5000,
        'start_date' => '2026-03-09 10:00:00',
        'end_date' => '2026-03-19 10:00:00',
        'initial_km' => 1000,
        'final_km' => 1500,
    ], $overrides));

    return CreateRentalDTO::fromRequest($request);
}

it('creates a rental with a generated uuid and the dto data', function () {
    $this->repository->shouldReceive('save')
        ->once()
        ->with(Mockery::on(static fn (Rental $rental): bool => $rental->uuid === TEST_CREATED_RENTAL_UUID
            && $rental->carUuid === TEST_RENTAL_CAR_UUID
            && $rental->clientUuid === TEST_RENTAL_CLIENT_UUID
            && $rental->dayPriceCents === 5000
            && $rental->startDate === '2026-03-09 10:00:00'
            && $rental->endDate === '2026-03-19 10:00:00'
            && $rental->initialKm === 1000
            && $rental->finalKm === 1500))
        ->andReturnUsing(static fn (Rental $rental): Rental => $rental);

    $result = $this->useCase->execute(createRentalDTO());

    expect($result->uuid)->toBe(TEST_CREATED_RENTAL_UUID)
        ->and($result->totalPrice)->toBe(500.0);
});

it('returns the rental persisted by the repository', function () {
    $persistedRental = Rental::restore(
        TEST_RENTAL_CAR_UUID,
        TEST_RENTAL_CLIENT_UUID,
        5000,
        '2026-03-09 10:00:00',
        '2026-03-19 10:00:00',
        1000,
        1500,
        TEST_CREATED_RENTAL_UUID,
    );

    $this->repository->shouldReceive('save')->once()->andReturn($persistedRental);

    expect($this->useCase->execute(createRentalDTO()))->toBe($persistedRental);
});

it('does not save the rental when the domain rejects it', function () {
    $this->repository->shouldNotReceive('save');

    $this->useCase->execute(createRentalDTO([
        'start_date' => '2026-03-19 10:00:00',
        'end_date' => '2026-03-09 10:00:00',
    ]));
})->throws(
    RentalDomainException::class,
    RentalError::INVALID_DATE_INTERVAL->message(),
);

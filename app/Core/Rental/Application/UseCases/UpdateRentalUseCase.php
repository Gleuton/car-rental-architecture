<?php

declare(strict_types=1);

namespace App\Core\Rental\Application\UseCases;

use App\Core\Rental\Application\DTOs\UpdateRentalDTO;
use App\Core\Rental\Domain\Entity\Rental;
use App\Core\Rental\Domain\Exceptions\RentalDomainException;
use App\Core\Rental\Domain\Repositories\RentalRepositoryInterface;
use App\Models\Car as EloquentCar;
use App\Models\Client as EloquentClient;

readonly class UpdateRentalUseCase
{
    public function __construct(
        private RentalRepositoryInterface $repository,
    ) {}

    /**
     * @throws RentalDomainException
     */
    public function execute(UpdateRentalDTO $dto): Rental
    {
        $rental = $this->repository->findByUuid($dto->uuid);

        $carId = $this->resolveCarId($dto);
        $clientId = $this->resolveClientId($dto);

        $updatedRental = Rental::restore(
            id: $rental->id,
            carId: $carId ?? $rental->carId,
            carUuid: $dto->carUuid ?? $rental->carUuid,
            clientId: $clientId ?? $rental->clientId,
            clientUuid: $dto->clientUuid ?? $rental->clientUuid,
            dayPriceCents: $dto->dayPriceCents ?? $rental->dayPriceCents,
            startDate: $dto->startDate ?? $rental->startDate,
            endDate: $dto->endDate ?? $rental->endDate,
            initialKm: $dto->initialKm ?? $rental->initialKm,
            finalKm: $dto->finalKm ?? $rental->finalKm,
            uuid: $rental->uuid,
        );

        return $this->repository->update($updatedRental);
    }

    private function resolveCarId(UpdateRentalDTO $dto): ?int
    {
        if ($dto->carUuid === null) {
            return null;
        }

        $carId = EloquentCar::query()->where('uuid', $dto->carUuid)->value('id');

        return $carId === null ? 0 : (int) $carId;
    }

    private function resolveClientId(UpdateRentalDTO $dto): ?int
    {

        if ($dto->clientUuid === null) {
            return null;
        }

        $clientId = EloquentClient::query()->where('uuid', $dto->clientUuid)->value('id');

        return $clientId === null ? 0 : (int) $clientId;
    }
}

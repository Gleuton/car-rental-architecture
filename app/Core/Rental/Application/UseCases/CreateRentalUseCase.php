<?php

declare(strict_types=1);

namespace App\Core\Rental\Application\UseCases;

use App\Core\Rental\Application\DTOs\CreateRentalDTO;
use App\Core\Rental\Domain\Entity\Rental;
use App\Core\Rental\Domain\Repositories\RentalRepositoryInterface;
use App\Models\Car as EloquentCar;
use App\Models\Client as EloquentClient;
use Exception;

readonly class CreateRentalUseCase
{
    public function __construct(
        private RentalRepositoryInterface $rentalRepository
    ) {}

    /**
     * @throws Exception
     */
    public function execute(CreateRentalDTO $dto): Rental
    {
        $carId = $this->resolveCarId($dto);
        $clientId = $this->resolveClientId($dto);

        $rental = Rental::new(
            $carId,
            $clientId,
            $dto->dayPriceCents,
            $dto->startDate,
            $dto->endDate,
            $dto->initialKm,
            $dto->finalKm,
        );

        return $this->rentalRepository->save($rental);
    }

    private function resolveCarId(CreateRentalDTO $dto): int
    {
        if ($dto->carId !== null) {
            return $dto->carId;
        }

        if ($dto->carUuid === null) {
            return 0;
        }

        $carId = EloquentCar::query()->where('uuid', $dto->carUuid)->value('id');

        return $carId === null ? 0 : (int) $carId;
    }

    private function resolveClientId(CreateRentalDTO $dto): int
    {
        if ($dto->clientId !== null) {
            return $dto->clientId;
        }

        if ($dto->clientUuid === null) {
            return 0;
        }

        $clientId = EloquentClient::query()->where('uuid', $dto->clientUuid)->value('id');

        return $clientId === null ? 0 : (int) $clientId;
    }
}

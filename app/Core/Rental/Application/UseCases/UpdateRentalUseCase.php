<?php

declare(strict_types=1);

namespace App\Core\Rental\Application\UseCases;

use App\Core\Rental\Application\DTOs\UpdateRentalDTO;
use App\Core\Rental\Domain\Entity\Rental;
use App\Core\Rental\Domain\Exceptions\RentalDomainException;
use App\Core\Rental\Domain\Repositories\RentalRepositoryInterface;

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
        $rental = $this->repository->findById($dto->id);

        $updatedRental = Rental::restore(
            id: $rental->id,
            carId: $dto->carId ?? $rental->carId,
            clientId: $dto->clientId ?? $rental->clientId,
            dayPriceCents: $dto->dayPriceCents ?? $rental->dayPriceCents,
            startDate: $dto->startDate ?? $rental->startDate,
            endDate: $dto->endDate ?? $rental->endDate,
            initialKm: $dto->initialKm ?? $rental->initialKm,
            finalKm: $dto->finalKm ?? $rental->finalKm,
        );

        return $this->repository->update($updatedRental);
    }
}

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
        $rental = $this->repository->findByUuid($dto->uuid);

        $updatedRental = Rental::restore(
            carUuid: $dto->carUuid ?? $rental->carUuid,
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
}

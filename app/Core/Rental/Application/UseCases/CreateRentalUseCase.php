<?php

declare(strict_types=1);

namespace App\Core\Rental\Application\UseCases;

use App\Core\Rental\Application\DTOs\CreateRentalDTO;
use App\Core\Rental\Domain\Entity\Rental;
use App\Core\Rental\Domain\Repositories\RentalRepositoryInterface;
use DateTime;
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
        $rental = Rental::new(
            $dto->carId,
            $dto->clientId,
            $dto->dayPriceCents,
            new DateTime($dto->startDate),
            new DateTime($dto->endDate),
            $dto->initialKm,
            $dto->finalKm,
        );

        return $this->rentalRepository->save($rental);
    }
}

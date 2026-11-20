<?php

declare(strict_types=1);

namespace App\Core\Rental\Application\UseCases;

use App\Core\Rental\Application\DTOs\CreateRentalDTO;
use App\Core\Rental\Domain\Entity\Rental;
use App\Core\Rental\Domain\Repositories\RentalRepositoryInterface;
use App\Core\Shared\Domain\Identity\UuidGeneratorInterface;
use Exception;

readonly class CreateRentalUseCase
{
    public function __construct(
        private RentalRepositoryInterface $rentalRepository,
        private UuidGeneratorInterface $uuidGenerator,
    ) {}

    /**
     * @throws Exception
     */
    public function execute(CreateRentalDTO $dto): Rental
    {
        $rental = Rental::new(
            $this->uuidGenerator->generate(),
            $dto->carUuid,
            $dto->clientUuid,
            $dto->dayPriceCents,
            $dto->startDate,
            $dto->endDate,
            $dto->initialKm,
            $dto->finalKm,
        );

        return $this->rentalRepository->save($rental);
    }
}

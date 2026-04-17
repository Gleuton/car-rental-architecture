<?php

declare(strict_types=1);

namespace App\Core\Rental\Application\UseCases;

use App\Core\Rental\Application\DTOs\RentalIdDTO;
use App\Core\Rental\Domain\Entity\Rental;
use App\Core\Rental\Domain\Repositories\RentalRepositoryInterface;

readonly class FindRentalByIdUseCase
{
    public function __construct(
        private RentalRepositoryInterface $repository,
    ) {}

    public function execute(RentalIdDTO $dto): Rental
    {
        return $this->repository->findByUuid($dto->uuid);
    }
}

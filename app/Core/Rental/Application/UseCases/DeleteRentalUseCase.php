<?php

declare(strict_types=1);

namespace App\Core\Rental\Application\UseCases;

use App\Core\Rental\Application\DTOs\RentalUuidDTO;
use App\Core\Rental\Domain\Repositories\RentalRepositoryInterface;

readonly class DeleteRentalUseCase
{
    public function __construct(
        private RentalRepositoryInterface $repository,
    ) {}

    public function execute(RentalUuidDTO $dto): void
    {
        $this->repository->deleteByUuid($dto->uuid);
    }
}

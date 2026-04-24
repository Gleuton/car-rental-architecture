<?php

declare(strict_types=1);

namespace App\Core\Car\Application\UseCases;

use App\Core\Car\Application\DTOs\CarUuidDTO;
use App\Core\Car\Domain\Entities\Car;
use App\Core\Car\Domain\Repositories\CarRepositoryInterface;

readonly class FindCarByUuidUseCase
{
    public function __construct(
        private CarRepositoryInterface $repository
    ) {}

    public function execute(CarUuidDTO $carDto): Car
    {
        return $this->repository->findByUuid($carDto->uuid);
    }
}

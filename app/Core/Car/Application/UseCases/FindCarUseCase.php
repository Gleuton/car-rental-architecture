<?php

declare(strict_types=1);

namespace App\Core\Car\Application\UseCases;

use App\Core\Car\Application\DTOs\CarIdDTO;
use App\Core\Car\Domain\Entity\Car;
use App\Core\Car\Domain\Repositories\CarRepositoryInterface;

readonly class FindCarUseCase
{
    public function __construct(
        private CarRepositoryInterface $repository
    ) {}

    public function execute(CarIdDTO $carDto): Car
    {
        return $this->repository->findByUuid($carDto->uuid);
    }
}

<?php

declare(strict_types=1);

namespace App\Core\Car\Application\UseCases;

use App\Core\Car\Application\DTOs\CreateCarDTO;
use App\Core\Car\Domain\Entity\Car;
use App\Core\Car\Domain\Repositories\CarRepositoryInterface;

readonly class CreateCarUseCase
{
    public function __construct(
        private CarRepositoryInterface $repository,
    ) {}

    public function execute(CreateCarDTO $dto): Car
    {
        $car = Car::new(
            $dto->carModelId,
            $dto->licensePlate,
            $dto->color,
            $dto->isAvailable,
            $dto->km,
        );

        return $this->repository->save($car);
    }
}

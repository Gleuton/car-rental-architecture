<?php

declare(strict_types=1);

namespace App\Core\Car\Application\UseCases;

use App\Core\Car\Application\DTOs\UpdateCarDto;
use App\Core\Car\Domain\Entity\Car;
use App\Core\Car\Domain\Repositories\CarRepositoryInterface;
use App\Core\Car\Domain\Roles\CarAlreadyExistsRole;

readonly class UpdateCarUseCase
{
    public function __construct(
        private CarRepositoryInterface $repository,
        private CarAlreadyExistsRole $carAlreadyExistsRole,
    ) {}

    public function execute(UpdateCarDto $dto): Car
    {
        $car = $this->repository->findById($dto->id);

        if ($dto->licensePlate && ($car->licensePlate !== $dto->licensePlate)) {
            $this->carAlreadyExistsRole->validate($dto->licensePlate);
        }

        $updatedCar = $car->update(
            licensePlate: $dto->licensePlate,
            color: $dto->color,
            isAvailable: $dto->isAvailable,
        );

        return $this->repository->update($updatedCar);
    }
}

<?php

declare(strict_types=1);

namespace App\Core\Car\Application\UseCases;

use App\Core\Car\Application\DTOs\UpdateCarDto;
use App\Core\Car\Domain\Entities\Car;
use App\Core\Car\Domain\Exceptions\CarDomainException;
use App\Core\Car\Domain\Repositories\CarRepositoryInterface;
use App\Core\Car\Domain\Roles\CarAlreadyExistsRole;

readonly class UpdateCarUseCase
{
    public function __construct(
        private CarRepositoryInterface $repository,
        private CarAlreadyExistsRole $carAlreadyExistsRole,
    ) {}

    /**
     * @throws CarDomainException
     */
    public function execute(UpdateCarDto $dto): Car
    {
        $car = $this->repository->findByUuid($dto->uuid);

        if ($dto->licensePlate && ($car->licensePlate() !== $dto->licensePlate)) {
            $this->carAlreadyExistsRole->validate($dto->licensePlate);
        }

        $car->changeLicensePlate($dto->licensePlate)
            ->changeColor($dto->color);

        if ($dto->isAvailable !== null) {
            $dto->isAvailable ? $car->markAsAvailable() : $car->markAsUnavailable();
        }

        return $this->repository->update($car);
    }
}

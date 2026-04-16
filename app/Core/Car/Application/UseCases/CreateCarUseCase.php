<?php

declare(strict_types=1);

namespace App\Core\Car\Application\UseCases;

use App\Core\Car\Application\DTOs\CreateCarDTO;
use App\Core\Car\Domain\Entity\Car;
use App\Core\Car\Domain\Exceptions\CarDomainException;
use App\Core\Car\Domain\Repositories\CarRepositoryInterface;
use App\Core\Car\Domain\Roles\CarAlreadyExistsRole;
use App\Models\CarModel as EloquentCarModel;

readonly class CreateCarUseCase
{
    public function __construct(
        private CarRepositoryInterface $repository,
        private CarAlreadyExistsRole $carAlreadyExistsRole,
    ) {}

    /**
     * @throws CarDomainException
     */
    public function execute(CreateCarDTO $dto): Car
    {
        $this->carAlreadyExistsRole->validate($dto->licensePlate);

        $carModelId = $this->resolveCarModelId($dto);

        $car = Car::new(
            $carModelId,
            $dto->licensePlate,
            $dto->color,
            $dto->isAvailable,
            $dto->km,
        );

        return $this->repository->save($car);
    }

    private function resolveCarModelId(CreateCarDTO $dto): int
    {
        if ($dto->carModelId !== null) {
            return $dto->carModelId;
        }

        if ($dto->carModelUuid === null) {
            return 0;
        }

        $carModelId = EloquentCarModel::query()
            ->where('uuid', $dto->carModelUuid)
            ->value('id');

        return $carModelId === null ? 0 : (int) $carModelId;
    }
}

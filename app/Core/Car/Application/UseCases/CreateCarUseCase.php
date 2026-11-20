<?php

declare(strict_types=1);

namespace App\Core\Car\Application\UseCases;

use App\Core\Car\Application\DTOs\CreateCarDTO;
use App\Core\Car\Domain\Entity\Car;
use App\Core\Car\Domain\Exceptions\CarDomainException;
use App\Core\Car\Domain\Repositories\CarRepositoryInterface;
use App\Core\Car\Domain\Roles\CarAlreadyExistsRole;
use App\Core\Car\Domain\Roles\ExistsCarModelRole;
use App\Core\Shared\Domain\Identity\UuidGeneratorInterface;

readonly class CreateCarUseCase
{
    public function __construct(
        private CarRepositoryInterface $repository,
        private CarAlreadyExistsRole $carAlreadyExistsRole,
        private ExistsCarModelRole $existsCarModelRole,
        private UuidGeneratorInterface $uuidGenerator,
    ) {}

    /**
     * @throws CarDomainException
     */
    public function execute(CreateCarDTO $dto): Car
    {
        $this->carAlreadyExistsRole->validate($dto->licensePlate);
        $this->existsCarModelRole->validate($dto->carModelUuid);

        $car = Car::new(
            $this->uuidGenerator->generate(),
            $dto->carModelUuid,
            $dto->licensePlate,
            $dto->color,
            $dto->isAvailable,
            $dto->km,
        );

        return $this->repository->save($car);
    }
}

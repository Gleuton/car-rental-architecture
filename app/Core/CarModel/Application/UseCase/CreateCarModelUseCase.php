<?php

namespace App\Core\CarModel\Application\UseCase;

use App\Core\CarModel\Application\DTOs\CreateCarModelDTO;
use App\Core\CarModel\Domain\Entity\CarModel;
use App\Core\CarModel\Domain\Repositories\CarModelRepositoryInterface;
use App\Core\Shared\Domain\Storage\FileStorageInterface;

readonly class CreateCarModelUseCase
{
    public function __construct(
        private FileStorageInterface $storage,
        private CarModelRepositoryInterface $repository
    )
    {
    }

    public function execute(CreateCarModelDTO $dto): CarModel
    {
        $imagePath = $this->storage->upload($dto->image, 'car_models');

        $carModel = CarModel::new(
            $dto->brandId,
            $dto->name,
            $imagePath,
            $dto->doorsNumber,
            $dto->seatsNumber,
            $dto->airbags,
            $dto->abs
        );

        $this->repository->save($carModel);

        return $carModel;
    }
}
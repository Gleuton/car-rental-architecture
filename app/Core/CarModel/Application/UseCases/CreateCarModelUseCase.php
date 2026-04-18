<?php

declare(strict_types=1);

namespace App\Core\CarModel\Application\UseCases;

use App\Core\Brand\Domain\Exceptions\BrandDomainException;
use App\Core\CarModel\Application\DTOs\CreateCarModelDTO;
use App\Core\CarModel\Domain\Entity\CarModel;
use App\Core\CarModel\Domain\Exceptions\CarModelDomainException;
use App\Core\CarModel\Domain\Repositories\CarModelRepositoryInterface;
use App\Core\CarModel\Domain\Roles\CarModelAlreadyExistsRole;
use App\Core\CarModel\Domain\Roles\ExistsBrandRole;
use App\Core\Shared\Domain\Storage\FileStorageInterface;
use App\Core\Shared\Infra\Adapters\LaravelUploadedFileAdapter;

readonly class CreateCarModelUseCase
{
    public function __construct(
        private FileStorageInterface $storage,
        private CarModelRepositoryInterface $repository,
        private ExistsBrandRole $existeBrandRole,
        private CarModelAlreadyExistsRole $carModelAlreadyRole
    ) {}

    /**
     * @throws BrandDomainException|CarModelDomainException
     */
    public function execute(CreateCarModelDTO $dto): CarModel
    {
        $this->existeBrandRole->validate($dto->brandUuid);
        $this->carModelAlreadyRole->validate($dto->name, $dto->brandUuid);

        $image = LaravelUploadedFileAdapter::adapt($dto->image);
        $imagePath = $this->storage->upload($image, 'car_models')->path;

        try {
            $carModel = CarModel::new(
                $dto->brandUuid,
                $dto->name,
                $imagePath,
                $dto->doorsNumber,
                $dto->seatsNumber,
                $dto->airbags,
                $dto->abs
            );

            return $this->repository->save($carModel);

        } catch (CarModelDomainException $e) {
            $this->storage->delete($imagePath);
            throw $e;
        }
    }
}

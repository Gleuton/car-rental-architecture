<?php

declare(strict_types=1);

namespace App\Core\CarModel\Application\UseCases;

use App\Core\Brand\Domain\Exceptions\BrandDomainException;
use App\Core\CarModel\Application\DTOs\UpdateCarModelDTO;
use App\Core\CarModel\Domain\Entity\CarModel;
use App\Core\CarModel\Domain\Exceptions\CarModelDomainException;
use App\Core\CarModel\Domain\Repositories\CarModelRepositoryInterface;
use App\Core\CarModel\Domain\Roles\CarModelAlreadyExistsRole;
use App\Core\CarModel\Domain\Roles\ExistsBrandRole;
use App\Core\Shared\Domain\Storage\FileStorageInterface;
use App\Core\Shared\Infra\Adapters\LaravelUploadedFileAdapter;

readonly class UpdateCarModelUseCase
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
    public function execute(UpdateCarModelDTO $dto): CarModel
    {
        if ($dto->brandId) {
            $this->existeBrandRole->validate($dto->brandId);
        }

        $carModel = $this->repository->findById($dto->id);

        if ($dto->name) {
            $this->carModelAlreadyRole->validate($dto->name, $dto->brandId ?? $carModel->brandId);
        }

        $imagePath = $this->updateImage($dto, $carModel, $carModel->image);

        $newCarModel = $carModel->update(
            $dto->brandId,
            $dto->name,
            $imagePath,
            $dto->doorsNumber,
            $dto->seatsNumber,
            $dto->airbags,
            $dto->abs
        );

        return $this->repository->update($newCarModel);
    }

    private function updateImage(UpdateCarModelDTO $carModelDTO, CarModel $brand, string $imagePath): string
    {
        if ($carModelDTO->image) {
            $image = LaravelUploadedFileAdapter::adapt($carModelDTO->image);
            $imagePath = $this->storage->upload($image, 'car_models')->path;
            $this->storage->delete($brand->image);
        }

        return $imagePath;
    }
}

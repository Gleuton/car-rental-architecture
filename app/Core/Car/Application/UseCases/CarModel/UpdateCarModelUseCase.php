<?php

declare(strict_types=1);

namespace App\Core\Car\Application\UseCases\CarModel;

use App\Core\Car\Application\DTOs\CarModel\UpdateCarModelDTO;
use App\Core\Car\Domain\Entities\CarModel;
use App\Core\Car\Domain\Exceptions\BrandDomainException;
use App\Core\Car\Domain\Exceptions\CarModelDomainException;
use App\Core\Car\Domain\Repositories\CarModelRepositoryInterface;
use App\Core\Car\Domain\Roles\CarModelAlreadyExistsRole;
use App\Core\Car\Domain\Roles\ExistsBrandRole;
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
        if ($dto->brandUuid) {
            $this->existeBrandRole->validate($dto->brandUuid);
        }

        $carModel = $this->repository->findByUuid($dto->uuid);

        if ($dto->name && ($dto->name !== $carModel->name)) {
            $this->carModelAlreadyRole->validate($dto->name, $dto->brandUuid ?? $carModel->brandUuid);
        }

        $imagePath = $this->updateImage($dto, $carModel, $carModel->image);

        $newCarModel = $carModel->update(
            $dto->brandUuid,
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

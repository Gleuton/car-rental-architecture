<?php

declare(strict_types=1);

namespace App\Core\Car\Application\UseCases\CarModel;

use App\Core\Car\Application\DTOs\CarModel\CreateCarModelDTO;
use App\Core\Car\Domain\Entity\CarModel;
use App\Core\Car\Domain\Exceptions\BrandDomainException;
use App\Core\Car\Domain\Exceptions\CarModelDomainException;
use App\Core\Car\Domain\Repositories\CarModelRepositoryInterface;
use App\Core\Car\Domain\Roles\CarModelAlreadyExistsRole;
use App\Core\Car\Domain\Roles\ExistsBrandRole;
use App\Core\Shared\Domain\Identity\UuidGeneratorInterface;
use App\Core\Shared\Domain\Storage\FileStorageInterface;
use App\Core\Shared\Infra\Adapters\LaravelUploadedFileAdapter;

readonly class CreateCarModelUseCase
{
    public function __construct(
        private FileStorageInterface $storage,
        private CarModelRepositoryInterface $repository,
        private ExistsBrandRole $existeBrandRole,
        private CarModelAlreadyExistsRole $carModelAlreadyRole,
        private UuidGeneratorInterface $uuidGenerator,
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
                $this->uuidGenerator->generate(),
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

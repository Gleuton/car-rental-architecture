<?php

declare(strict_types=1);

namespace App\Core\CarModel\Application\UseCases;

use App\Core\CarModel\Application\DTOs\CarModelIdDTO;
use App\Core\CarModel\Domain\Entity\CarModel;
use App\Core\CarModel\Domain\Repositories\CarModelRepositoryInterface;

readonly class FindCarModelByIdUseCase
{
    public function __construct(
        private CarModelRepositoryInterface $repository
    ) {}

    public function execute(CarModelIdDTO $idDTO): CarModel
    {
        return $this->repository->findById($idDTO->id);
    }
}

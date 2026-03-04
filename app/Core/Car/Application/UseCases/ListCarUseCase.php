<?php

declare(strict_types=1);

namespace App\Core\Car\Application\UseCases;

use App\Core\Car\Application\DTOs\ListCarDTO;
use App\Core\Car\Domain\Entity\CarCollection;
use App\Core\Car\Domain\Entity\CarFilter;
use App\Core\Car\Domain\Repositories\CarRepositoryInterface;
use App\Core\Shared\Application\Pagination\PaginatedResult;

readonly class ListCarUseCase
{
    public function __construct(
        private CarRepositoryInterface $repository
    ) {}

    /**
     * @return PaginatedResult<CarCollection>
     */
    public function execute(ListCarDTO $dto): PaginatedResult
    {
        $filters = CarFilter::create(
            $dto->licensePlate,
            $dto->orderBy,
            $dto->direction,
            $dto->perPage,
            $dto->page
        );

        return $this->repository->listCars($filters);
    }
}

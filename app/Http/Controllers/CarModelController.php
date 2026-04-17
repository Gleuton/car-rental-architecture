<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Brand\Domain\Exceptions\BrandDomainException;
use App\Core\CarModel\Application\DTOs\CarModelUuidDTO;
use App\Core\CarModel\Application\DTOs\CreateCarModelDTO;
use App\Core\CarModel\Application\DTOs\FilterCarModelDTO;
use App\Core\CarModel\Application\DTOs\UpdateCarModelDTO;
use App\Core\CarModel\Application\UseCases\CreateCarModelUseCase;
use App\Core\CarModel\Application\UseCases\DeleteCarModelUseCase;
use App\Core\CarModel\Application\UseCases\FindCarModelByUuidUseCase;
use App\Core\CarModel\Application\UseCases\ListCarModelsUseCase;
use App\Core\CarModel\Application\UseCases\UpdateCarModelUseCase;
use App\Core\CarModel\Domain\Exceptions\CarModelDomainException;
use App\Http\Requests\CarModel\IndexCarModelRequest;
use App\Http\Requests\CarModel\StoreCarModelRequest;
use App\Http\Requests\CarModel\UpdateCarModelRequest;
use App\Http\Resources\CarModelResource;
use Illuminate\Http\JsonResponse;

class CarModelController extends Controller
{
    public function __construct(
        private readonly ListCarModelsUseCase $listCarModels,
        private readonly CreateCarModelUseCase $createCarModel,
        private readonly UpdateCarModelUseCase $updateCarModel,
        private readonly FindCarModelByUuidUseCase $findCarModel,
        private readonly DeleteCarModelUseCase $deleteCarModel,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(IndexCarModelRequest $request): JsonResponse
    {
        $filters = FilterCarModelDTO::fromRequest($request);

        $carModels = $this->listCarModels->execute($filters);

        return response()->json([
            'data' => array_map(static fn ($carModel) => CarModelResource::toArray($carModel), $carModels->items->all()),
            'meta' => [
                'current_page' => $carModels->page,
                'per_page' => $carModels->perPage,
                'total' => $carModels->total,
                'last_page' => $carModels->lastPage,
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCarModelRequest $request): JsonResponse
    {
        $carModelDTO = CreateCarModelDTO::fromRequest($request);
        $carModel = $this->createCarModel->execute($carModelDTO);

        return response()->json(['data' => CarModelResource::toArray($carModel)]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $carModelUuid): JsonResponse
    {
        $uuidDTO = CarModelUuidDTO::fromUuid($carModelUuid);
        $carModel = $this->findCarModel->execute($uuidDTO);

        return response()->json(['data' => CarModelResource::toArray($carModel)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @throws BrandDomainException|CarModelDomainException
     */
    public function update(UpdateCarModelRequest $request, string $carModelUuid): JsonResponse
    {
        $carModelDTO = UpdateCarModelDTO::fromRequest($request, $carModelUuid);
        $carModel = $this->updateCarModel->execute($carModelDTO);

        return response()->json(['data' => CarModelResource::toArray($carModel)]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $carModelUuid): JsonResponse
    {
        $uuidDTO = CarModelUuidDTO::fromUuid($carModelUuid);

        $this->deleteCarModel->execute($uuidDTO);

        return response()->json([], 204);
    }
}

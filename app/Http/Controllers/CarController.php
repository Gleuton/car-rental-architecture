<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Car\Application\DTOs\CarUuidDTO;
use App\Core\Car\Application\DTOs\CreateCarDTO;
use App\Core\Car\Application\DTOs\ListCarDTO;
use App\Core\Car\Application\DTOs\UpdateCarDto;
use App\Core\Car\Application\UseCases\CreateCarUseCase;
use App\Core\Car\Application\UseCases\DeleteCarByUuidUseCase;
use App\Core\Car\Application\UseCases\FindCarByUuidUseCase;
use App\Core\Car\Application\UseCases\ListCarUseCase;
use App\Core\Car\Application\UseCases\UpdateCarUseCase;
use App\Core\Car\Domain\Exceptions\CarDomainException;
use App\Http\Requests\Car\IndexCarRequest;
use App\Http\Requests\Car\StoreCarRequest;
use App\Http\Requests\Car\UpdateCarRequest;
use App\Http\Resources\CarResource;
use Illuminate\Http\JsonResponse;

class CarController extends Controller
{
    public function __construct(
        private readonly CreateCarUseCase $createCar,
        private readonly FindCarByUuidUseCase $findCar,
        private readonly ListCarUseCase $listCar,
        private readonly DeleteCarByUuidUseCase $deleteCar,
        private readonly UpdateCarUseCase $updateCar,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(IndexCarRequest $request): JsonResponse
    {
        $dto = ListCarDTO::fromRequest($request);
        $cars = $this->listCar->execute($dto);

        return response()->json(CarResource::PaginatedToArray($cars));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @throws CarDomainException
     */
    public function store(StoreCarRequest $request): JsonResponse
    {
        $dto = CreateCarDTO::fromRequest($request);
        $car = $this->createCar->execute($dto);

        return response()->json(['data' => CarResource::CarToArray($car)]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $carUuid): JsonResponse
    {
        $carDto = CarUuidDTO::fromUuid($carUuid);
        $car = $this->findCar->execute($carDto);

        return response()->json(['data' => CarResource::CarToArray($car)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @throws CarDomainException
     */
    public function update(UpdateCarRequest $request, string $carUuid): JsonResponse
    {
        $carDto = UpdateCarDto::fromRequest($request, $carUuid);
        $car = $this->updateCar->execute($carDto);

        return response()->json([
            'data' => CarResource::CarToArray($car),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $carUuid): JsonResponse
    {
        $carDto = CarUuidDTO::fromUuid($carUuid);

        $this->deleteCar->execute($carDto);

        return response()->json([], 204);
    }
}

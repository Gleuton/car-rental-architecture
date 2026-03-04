<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Car\Application\DTOs\CarIdDTO;
use App\Core\Car\Application\DTOs\CreateCarDTO;
use App\Core\Car\Application\DTOs\ListCarDTO;
use App\Core\Car\Application\UseCases\CreateCarUseCase;
use App\Core\Car\Application\UseCases\DeleteCarUseCase;
use App\Core\Car\Application\UseCases\FindCarUseCase;
use App\Core\Car\Application\UseCases\ListCarUseCase;
use App\Http\Requests\Car\IndexCarRequest;
use App\Http\Requests\Car\StoreCarRequest;
use App\Models\Car;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function __construct(
        private readonly CreateCarUseCase $createCar,
        private readonly FindCarUseCase $findCar,
        private readonly ListCarUseCase $listCar,
        private readonly DeleteCarUseCase $deleteCar,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(IndexCarRequest $request): jsonResponse
    {
        $dto = ListCarDTO::fromRequest($request);
        $cars = $this->listCar->execute($dto);

        return response()->json([
            'data' => $cars->items,
            'meta' => [
                'current_page' => $cars->page,
                'per_page' => $cars->perPage,
                'total' => $cars->total,
                'last_page' => $cars->lastPage,
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCarRequest $request): JsonResponse
    {
        $dto = CreateCarDTO::fromRequest($request);
        $car = $this->createCar->execute($dto);

        return response()->json(['data' => $car]);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $carId): JsonResponse
    {
        $carDto = CarIdDTO::fromId($carId);
        $car = $this->findCar->execute($carDto);

        return response()->json([
            'data' => $car,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Car $car)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $carId): JsonResponse
    {
        $carDto = CarIdDTO::fromId($carId);

        $this->deleteCar->execute($carDto);

        return response()->json([], 204);
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Brand\Domain\Exceptions\BrandDomainException;
use App\Core\CarModel\Application\DTOs\CreateCarModelDTO;
use App\Core\CarModel\Application\DTOs\UpdateCarModelDTO;
use App\Core\CarModel\Application\UseCases\CreateCarModelUseCase;
use App\Core\CarModel\Application\UseCases\UpdateCarModelUseCase;
use App\Core\CarModel\Domain\Exceptions\CarModelDomainException;
use App\Http\Requests\CarModel\StoreCarModelRequest;
use App\Http\Requests\CarModel\UpdateCarModelRequest;
use App\Models\CarModel;
use Illuminate\Http\JsonResponse;

class CarModelController extends Controller
{
    public function __construct(
        private readonly CreateCarModelUseCase $createCarModel,
        private readonly UpdateCarModelUseCase $updateCarModel
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCarModelRequest $request): JsonResponse
    {
        $carModelDTO = CreateCarModelDTO::fromRequest($request);
        $carModel = $this->createCarModel->execute($carModelDTO);

        return response()->json(['data' => $carModel]);
    }

    /**
     * Display the specified resource.
     */
    public function show(CarModel $carModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @throws BrandDomainException|CarModelDomainException
     */
    public function update(UpdateCarModelRequest $request, int $carModelId): JsonResponse
    {
        $carModelDTO = UpdateCarModelDTO::fromRequest($request, $carModelId);
        $carModel = $this->updateCarModel->execute($carModelDTO);

        return response()->json(['data' => $carModel]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CarModel $carModel)
    {
        //
    }
}

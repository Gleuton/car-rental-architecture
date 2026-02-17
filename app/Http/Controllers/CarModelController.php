<?php

namespace App\Http\Controllers;

use App\Core\CarModel\Application\DTOs\CreateCarModelDTO;
use App\Core\CarModel\Application\UseCase\CreateCarModelUseCase;
use App\Http\Requests\CarModel\StoreCarModelRequest;
use App\Models\CarModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CarModelController extends Controller
{
    public function __construct(
        private readonly CreateCarModelUseCase $createCarModel
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCarModelRequest $request): JsonResponse
    {
        $carModelDTO = CreateCarModelDTO::fromRequest($request);
        $carModel = $this->createCarModel->execute($carModelDTO);

        return response()->json($carModel);
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
     */
    public function update(Request $request, CarModel $carModel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CarModel $carModel)
    {
        //
    }
}

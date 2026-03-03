<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Car\Application\DTOs\CreateCarDTO;
use App\Core\Car\Application\UseCases\CreateCarUseCase;
use App\Http\Requests\Car\StoreCarRequest;
use App\Models\Car;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function __construct(
        private readonly CreateCarUseCase $createCar,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(Car $car)
    {
        //
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
    public function destroy(Car $car)
    {
        //
    }
}

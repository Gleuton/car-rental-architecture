<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Rental\Application\DTOs\CreateRentalDTO;
use App\Core\Rental\Application\UseCases\CreateRentalUseCase;
use App\Http\Requests\Rental\StoreRentalRequest;
use App\Models\Rental;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RentalController extends Controller
{
    public function __construct(
        private readonly CreateRentalUseCase $createRentalUseCase,
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
    public function store(StoreRentalRequest $request): JsonResponse
    {
        $dto = CreateRentalDTO::fromRequest($request);
        $rental = $this->createRentalUseCase->execute($dto);

        return response()->json(['data' => $rental], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Rental $rental)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rental $rental)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rental $rental)
    {
        //
    }
}

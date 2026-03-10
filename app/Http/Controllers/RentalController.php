<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Rental\Application\DTOs\CreateRentalDTO;
use App\Core\Rental\Application\DTOs\FilterRentalDTO;
use App\Core\Rental\Application\DTOs\RentalIdDTO;
use App\Core\Rental\Application\UseCases\CreateRentalUseCase;
use App\Core\Rental\Application\UseCases\FindRentalByIdUseCase;
use App\Core\Rental\Application\UseCases\ListRentalsUseCase;
use App\Http\Requests\Rental\IndexRentalRequest;
use App\Http\Requests\Rental\StoreRentalRequest;
use App\Models\Rental;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RentalController extends Controller
{
    public function __construct(
        private readonly CreateRentalUseCase $createRentalUseCase,
        private readonly ListRentalsUseCase $listRentalsUseCase,
        private readonly FindRentalByIdUseCase $findRentalByIdUseCase,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRentalRequest $request): JsonResponse
    {
        $filters = FilterRentalDTO::fromRequest($request);
        $rentals = $this->listRentalsUseCase->execute($filters);

        return response()->json([
            'data' => $rentals->items,
            'meta' => [
                'current_page' => $rentals->page,
                'per_page' => $rentals->perPage,
                'total' => $rentals->total,
                'last_page' => $rentals->lastPage,
            ],
        ]);
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
    public function show(int $rentalId): JsonResponse
    {
        $dto = RentalIdDTO::fromId($rentalId);
        $rental = $this->findRentalByIdUseCase->execute($dto);

        return response()->json(['data' => $rental]);
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

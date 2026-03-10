<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Rental\Application\DTOs\CreateRentalDTO;
use App\Core\Rental\Application\DTOs\FilterRentalDTO;
use App\Core\Rental\Application\DTOs\RentalIdDTO;
use App\Core\Rental\Application\DTOs\UpdateRentalDTO;
use App\Core\Rental\Application\UseCases\CreateRentalUseCase;
use App\Core\Rental\Application\UseCases\DeleteRentalUseCase;
use App\Core\Rental\Application\UseCases\FindRentalByIdUseCase;
use App\Core\Rental\Application\UseCases\ListRentalsUseCase;
use App\Core\Rental\Application\UseCases\UpdateRentalUseCase;
use App\Http\Requests\Rental\IndexRentalRequest;
use App\Http\Requests\Rental\StoreRentalRequest;
use App\Http\Requests\Rental\UpdateRentalRequest;
use Illuminate\Http\JsonResponse;

class RentalController extends Controller
{
    public function __construct(
        private readonly CreateRentalUseCase $createRentalUseCase,
        private readonly ListRentalsUseCase $listRentalsUseCase,
        private readonly FindRentalByIdUseCase $findRentalByIdUseCase,
        private readonly DeleteRentalUseCase $deleteRentalUseCase,
        private readonly UpdateRentalUseCase $updateRentalUseCase,
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
    public function update(UpdateRentalRequest $request, int $rentalId): JsonResponse
    {
        $dto = UpdateRentalDTO::fromRequest($request, $rentalId);
        $rental = $this->updateRentalUseCase->execute($dto);

        return response()->json(['data' => $rental]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $rentalId): JsonResponse
    {
        $dto = RentalIdDTO::fromId($rentalId);

        $this->deleteRentalUseCase->execute($dto);

        return response()->json([], 204);
    }
}

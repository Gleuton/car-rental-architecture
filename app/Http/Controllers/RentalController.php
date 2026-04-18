<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Rental\Application\DTOs\CreateRentalDTO;
use App\Core\Rental\Application\DTOs\FilterRentalDTO;
use App\Core\Rental\Application\DTOs\RentalUuidDTO;
use App\Core\Rental\Application\DTOs\UpdateRentalDTO;
use App\Core\Rental\Application\UseCases\CreateRentalUseCase;
use App\Core\Rental\Application\UseCases\DeleteRentalUseCase;
use App\Core\Rental\Application\UseCases\FindRentalByUuidUseCase;
use App\Core\Rental\Application\UseCases\ListRentalsUseCase;
use App\Core\Rental\Application\UseCases\UpdateRentalUseCase;
use App\Http\Requests\Rental\IndexRentalRequest;
use App\Http\Requests\Rental\StoreRentalRequest;
use App\Http\Requests\Rental\UpdateRentalRequest;
use App\Http\Resources\RentalResource;
use Illuminate\Http\JsonResponse;

class RentalController extends Controller
{
    public function __construct(
        private readonly CreateRentalUseCase $createRentalUseCase,
        private readonly ListRentalsUseCase $listRentalsUseCase,
        private readonly FindRentalByUuidUseCase $findRentalByUuidUseCase,
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

        return response()->json(RentalResource::PaginatedToArray($rentals));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRentalRequest $request): JsonResponse
    {
        $dto = CreateRentalDTO::fromRequest($request);
        $rental = $this->createRentalUseCase->execute($dto);

        return response()->json(['data' => RentalResource::toArray($rental)], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $rental): JsonResponse
    {
        $dto = RentalUuidDTO::fromUuid($rental);
        $foundRental = $this->findRentalByUuidUseCase->execute($dto);

        return response()->json(['data' => RentalResource::toArray($foundRental)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRentalRequest $request, string $rental): JsonResponse
    {
        $dto = UpdateRentalDTO::fromRequest($request, $rental);
        $updatedRental = $this->updateRentalUseCase->execute($dto);

        return response()->json(['data' => RentalResource::toArray($updatedRental)]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $rental): JsonResponse
    {
        $dto = RentalUuidDTO::fromUuid($rental);

        $this->deleteRentalUseCase->execute($dto);

        return response()->json([], 204);
    }
}

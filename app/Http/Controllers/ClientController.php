<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Client\Application\DTOs\ClientIdDTO;
use App\Core\Client\Application\DTOs\CreateClientDTO;
use App\Core\Client\Application\DTOs\FilterClientDTO;
use App\Core\Client\Application\DTOs\UpdateClientDTO;
use App\Core\Client\Application\UseCases\CreateClientUseCase;
use App\Core\Client\Application\UseCases\DeleteClientUseCase;
use App\Core\Client\Application\UseCases\FindClientByIdUseCase;
use App\Core\Client\Application\UseCases\ListClientsUseCase;
use App\Core\Client\Application\UseCases\UpdateClientUseCase;
use App\Core\Client\Domain\Exceptions\ClientDomainException;
use App\Http\Requests\Client\IndexClientRequest;
use App\Http\Requests\Client\StoreClientRequest;
use App\Http\Requests\Client\UpdateClientRequest;
use Illuminate\Http\JsonResponse;

class ClientController extends Controller
{
    public function __construct(
        private readonly ListClientsUseCase $listClientsUseCase,
        private readonly CreateClientUseCase $createClientUseCase,
        private readonly FindClientByIdUseCase $findClientByIdUseCase,
        private readonly DeleteClientUseCase $deleteClientUseCase,
        private readonly UpdateClientUseCase $updateClientUseCase,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(IndexClientRequest $request): JsonResponse
    {
        $filters = FilterClientDTO::fromRequest($request);

        $clients = $this->listClientsUseCase->execute($filters);

        return response()->json([
            'data' => $clients->items,
            'meta' => [
                'current_page' => $clients->page,
                'per_page' => $clients->perPage,
                'total' => $clients->total,
                'last_page' => $clients->lastPage,
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @throws ClientDomainException
     */
    public function store(StoreClientRequest $request): JsonResponse
    {
        $dto = CreateClientDTO::fromRequest($request);

        $client = $this->createClientUseCase->execute($dto);

        return response()->json(['data' => $client], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $clientId): JsonResponse
    {
        $dto = ClientIdDTO::fromId($clientId);

        $client = $this->findClientByIdUseCase->execute($dto);

        return response()->json(['data' => $client]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @throws ClientDomainException
     */
    public function update(UpdateClientRequest $request, int $clientId): JsonResponse
    {
        $dto = UpdateClientDTO::fromRequest($request, $clientId);

        $client = $this->updateClientUseCase->execute($dto);

        return response()->json(['data' => $client]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $clientId): JsonResponse
    {
        $dto = ClientIdDTO::fromId($clientId);

        $this->deleteClientUseCase->execute($dto);

        return response()->json([], 204);
    }
}

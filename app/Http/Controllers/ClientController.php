<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Client\Application\DTOs\ClientUuidDTO;
use App\Core\Client\Application\DTOs\CreateClientDTO;
use App\Core\Client\Application\DTOs\FilterClientDTO;
use App\Core\Client\Application\DTOs\UpdateClientDTO;
use App\Core\Client\Application\UseCases\CreateClientUseCase;
use App\Core\Client\Application\UseCases\DeleteClientByUuidUseCase;
use App\Core\Client\Application\UseCases\FindClientByUuidUseCase;
use App\Core\Client\Application\UseCases\ListClientsUseCase;
use App\Core\Client\Application\UseCases\UpdateClientUseCase;
use App\Core\Client\Domain\Exceptions\ClientDomainException;
use App\Http\Requests\Client\IndexClientRequest;
use App\Http\Requests\Client\StoreClientRequest;
use App\Http\Requests\Client\UpdateClientRequest;
use App\Http\Resources\ClientResource;
use Illuminate\Http\JsonResponse;

class ClientController extends Controller
{
    public function __construct(
        private readonly ListClientsUseCase $listClientsUseCase,
        private readonly CreateClientUseCase $createClientUseCase,
        private readonly FindClientByUuidUseCase $findClientByUuidUseCase,
        private readonly DeleteClientByUuidUseCase $deleteClientUseCase,
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
            'data' => array_map(static fn ($client) => ClientResource::toArray($client), $clients->items->all()),
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

        return response()->json(['data' => ClientResource::toArray($client)], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $client): JsonResponse
    {
        $dto = ClientUuidDTO::fromUuid($client);

        $foundClient = $this->findClientByUuidUseCase->execute($dto);

        return response()->json(['data' => ClientResource::toArray($foundClient)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @throws ClientDomainException
     */
    public function update(UpdateClientRequest $request, string $client): JsonResponse
    {
        $dto = UpdateClientDTO::fromRequest($request, $client);

        $updatedClient = $this->updateClientUseCase->execute($dto);

        return response()->json(['data' => ClientResource::toArray($updatedClient)]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $client): JsonResponse
    {
        $dto = ClientUuidDTO::fromUuid($client);

        $this->deleteClientUseCase->execute($dto);

        return response()->json([], 204);
    }
}

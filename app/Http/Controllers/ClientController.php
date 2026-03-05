<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Client\Application\DTOs\CreateClientDTO;
use App\Core\Client\Application\DTOs\FilterClientDTO;
use App\Core\Client\Application\UseCases\CreateClientUseCase;
use App\Core\Client\Application\UseCases\ListClientsUseCase;
use App\Core\Client\Domain\Exceptions\ClientDomainException;
use App\Http\Requests\Client\IndexClientRequest;
use App\Http\Requests\Client\StoreClientRequest;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function __construct(
        private readonly ListClientsUseCase $listClientsUseCase,
        private readonly CreateClientUseCase $createClientUseCase,
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
    public function show(Client $client)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        //
    }
}

<?php

declare(strict_types=1);

use App\Core\Client\Application\DTOs\CreateClientDTO;
use App\Core\Client\Application\UseCases\CreateClientUseCase;
use App\Core\Client\Domain\Entity\Client;
use App\Core\Client\Domain\Exceptions\ClientDomainException;
use App\Core\Client\Domain\Repositories\ClientRepositoryInterface;
use App\Core\Shared\Domain\Identity\UuidGeneratorInterface;
use App\Core\Shared\Domain\ValueObjects\Uuid;
use App\Http\Requests\Client\StoreClientRequest;

beforeEach(function () {
    $this->generatedUuid = '11111111-1111-4111-8111-111111111111';

    $this->repository = Mockery::mock(ClientRepositoryInterface::class);
    $this->uuidGenerator = Mockery::mock(UuidGeneratorInterface::class);

    $this->useCase = new CreateClientUseCase($this->repository, $this->uuidGenerator);
});

function createClientDTO(string $name): CreateClientDTO
{
    $request = Mockery::mock(StoreClientRequest::class);
    $request->shouldReceive('input')->with('name')->andReturn($name);

    return CreateClientDTO::fromRequest($request);
}

it('creates a client with a generated uuid', function () {
    $this->uuidGenerator->shouldReceive('generate')
        ->once()
        ->andReturn(Uuid::fromString($this->generatedUuid));

    $expectedClient = Client::restore('John Doe', $this->generatedUuid);

    $this->repository->shouldReceive('save')
        ->once()
        ->with(Mockery::on(fn (Client $client) => $client->name === 'John Doe'
            && $client->uuid === $this->generatedUuid))
        ->andReturn($expectedClient);

    $result = $this->useCase->execute(createClientDTO('John Doe'));

    expect($result)->toBe($expectedClient);
});

it('does not save the client when the name is invalid', function () {
    $this->uuidGenerator->shouldReceive('generate')
        ->andReturn(Uuid::fromString($this->generatedUuid));

    $this->repository->shouldNotReceive('save');

    $this->useCase->execute(createClientDTO(''));
})->throws(ClientDomainException::class, 'Client name cannot be empty');

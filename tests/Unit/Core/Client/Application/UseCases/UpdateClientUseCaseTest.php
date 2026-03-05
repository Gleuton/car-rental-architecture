<?php

declare(strict_types=1);

use App\Core\Client\Application\DTOs\UpdateClientDTO;
use App\Core\Client\Application\UseCases\UpdateClientUseCase;
use App\Core\Client\Domain\Entity\Client;
use App\Core\Client\Domain\Repositories\ClientRepositoryInterface;
use App\Http\Requests\Client\UpdateClientRequest;

it('updates a client name successfully', function () {
    $request = Mockery::mock(UpdateClientRequest::class);

    $request->shouldReceive('input')->with('name')->andReturn('John Updated');

    $dto = UpdateClientDTO::fromRequest($request, 1);

    $repository = Mockery::mock(ClientRepositoryInterface::class);
    $existingClient = Client::restore(1, 'John Doe');
    $updatedClient = Client::restore(1, 'John Updated');

    $repository->shouldReceive('findById')
        ->with(1)
        ->once()
        ->andReturn($existingClient);

    $repository->shouldReceive('update')
        ->with(Mockery::on(static function (Client $client) {
            return $client->id === 1 && $client->name === 'John Updated';
        }))
        ->once()
        ->andReturn($updatedClient);

    $useCase = new UpdateClientUseCase($repository);
    $result = $useCase->execute($dto);

    expect($result->name)->toBe('John Updated')
        ->and($result->id)->toBe(1);
});

it('propagates exception when client is not found during update', function () {
    $request = Mockery::mock(UpdateClientRequest::class);

    $request->shouldReceive('input')->with('name')->andReturn('John Updated');

    $dto = UpdateClientDTO::fromRequest($request, 999);

    $repository = Mockery::mock(ClientRepositoryInterface::class);
    $repository->shouldReceive('findById')
        ->with(999)
        ->once()
        ->andThrow(new RuntimeException('Client not found'));

    $useCase = new UpdateClientUseCase($repository);

    expect(fn () => $useCase->execute($dto))
        ->toThrow(RuntimeException::class);
});

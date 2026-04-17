<?php

declare(strict_types=1);

use App\Core\Client\Application\DTOs\UpdateClientDTO;
use App\Core\Client\Application\UseCases\UpdateClientUseCase;
use App\Core\Client\Domain\Entity\Client;
use App\Core\Client\Domain\Repositories\ClientRepositoryInterface;
use App\Http\Requests\Client\UpdateClientRequest;
use Illuminate\Support\Str;

it('updates a client name successfully', function () {
    $request = Mockery::mock(UpdateClientRequest::class);

    $request->shouldReceive('input')->with('name')->andReturn('John Updated');

    $uuid = (string) Str::uuid();
    $dto = UpdateClientDTO::fromRequest($request, $uuid);

    $repository = Mockery::mock(ClientRepositoryInterface::class);
    $existingClient = Client::restore(1, 'John Doe');
    $updatedClient = Client::restore(1, 'John Updated');

    $repository->shouldReceive('findByUuid')
        ->with($uuid)
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

    $uuid = (string) Str::uuid();
    $dto = UpdateClientDTO::fromRequest($request, $uuid);

    $repository = Mockery::mock(ClientRepositoryInterface::class);
    $repository->shouldReceive('findByUuid')
        ->with($uuid)
        ->once()
        ->andThrow(new RuntimeException('Client not found'));

    $useCase = new UpdateClientUseCase($repository);

    expect(fn () => $useCase->execute($dto))
        ->toThrow(RuntimeException::class);
});

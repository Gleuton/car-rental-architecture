<?php

declare(strict_types=1);

use App\Core\Client\Application\DTOs\ClientIdDTO;
use App\Core\Client\Application\UseCases\FindClientByIdUseCase;
use App\Core\Client\Domain\Entity\Client;
use App\Core\Client\Domain\Repositories\ClientRepositoryInterface;
use Illuminate\Support\Str;

it('finds a client by UUID successfully', function () {
    $uuid = (string) Str::uuid();
    $dto = ClientIdDTO::fromUuid($uuid);

    $repository = Mockery::mock(ClientRepositoryInterface::class);

    $expectedClient = Client::restore(1, 'John Doe');
    $repository->shouldReceive('findByUuid')
        ->with($uuid)
        ->once()
        ->andReturn($expectedClient);

    $useCase = new FindClientByIdUseCase($repository);
    $result = $useCase->execute($dto);

    expect($result)->toBe($expectedClient);
});

it('propagates exception when client is not found', function () {
    $uuid = (string) Str::uuid();
    $dto = ClientIdDTO::fromUuid($uuid);

    $repository = Mockery::mock(ClientRepositoryInterface::class);
    $repository->shouldReceive('findByUuid')
        ->with($uuid)
        ->once()
        ->andThrow(new RuntimeException('Client not found'));

    $useCase = new FindClientByIdUseCase($repository);

    expect(fn () => $useCase->execute($dto))
        ->toThrow(RuntimeException::class);
});

<?php

declare(strict_types=1);

use App\Core\Client\Application\DTOs\ClientIdDTO;
use App\Core\Client\Application\UseCases\FindClientByIdUseCase;
use App\Core\Client\Domain\Entity\Client;
use App\Core\Client\Domain\Repositories\ClientRepositoryInterface;

it('finds a client by ID successfully', function () {
    $dto = ClientIdDTO::fromId(1);

    $repository = Mockery::mock(ClientRepositoryInterface::class);

    $expectedClient = Client::restore(1, 'John Doe');
    $repository->shouldReceive('findById')
        ->with(1)
        ->once()
        ->andReturn($expectedClient);

    $useCase = new FindClientByIdUseCase($repository);
    $result = $useCase->execute($dto);

    expect($result)->toBe($expectedClient);
});

it('propagates exception when client is not found', function () {
    $dto = ClientIdDTO::fromId(999);

    $repository = Mockery::mock(ClientRepositoryInterface::class);
    $repository->shouldReceive('findById')
        ->with(999)
        ->once()
        ->andThrow(new RuntimeException('Client not found'));

    $useCase = new FindClientByIdUseCase($repository);

    expect(fn () => $useCase->execute($dto))
        ->toThrow(RuntimeException::class);
});

<?php

declare(strict_types=1);

use App\Core\Client\Application\DTOs\ClientIdDTO;
use App\Core\Client\Application\UseCases\DeleteClientUseCase;
use App\Core\Client\Domain\Repositories\ClientRepositoryInterface;

it('deletes a client successfully', function () {
    $dto = ClientIdDTO::fromId(1);

    $repository = Mockery::mock(ClientRepositoryInterface::class);
    $repository->shouldReceive('delete')
        ->with(1)
        ->once();

    $useCase = new DeleteClientUseCase($repository);
    $useCase->execute($dto);

    expect(true)->toBeTrue();
});

it('propagates exception when client is not found during delete', function () {
    $dto = ClientIdDTO::fromId(999);

    $repository = Mockery::mock(ClientRepositoryInterface::class);
    $repository->shouldReceive('delete')
        ->with(999)
        ->once()
        ->andThrow(new RuntimeException('Client not found'));

    $useCase = new DeleteClientUseCase($repository);

    expect(fn () => $useCase->execute($dto))
        ->toThrow(RuntimeException::class);
});

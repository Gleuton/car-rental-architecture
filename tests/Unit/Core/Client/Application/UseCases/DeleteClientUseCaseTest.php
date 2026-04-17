<?php

declare(strict_types=1);

use App\Core\Client\Application\DTOs\ClientIdDTO;
use App\Core\Client\Application\UseCases\DeleteClientUseCase;
use App\Core\Client\Domain\Repositories\ClientRepositoryInterface;
use Illuminate\Support\Str;

it('deletes a client successfully', function () {
    $uuid = (string) Str::uuid();
    $dto = ClientIdDTO::fromUuid($uuid);

    $repository = Mockery::mock(ClientRepositoryInterface::class);
    $repository->shouldReceive('deleteByUuid')
        ->with($uuid)
        ->once();

    $useCase = new DeleteClientUseCase($repository);
    $useCase->execute($dto);

    expect(true)->toBeTrue();
});

it('propagates exception when client is not found during delete', function () {
    $uuid = (string) Str::uuid();
    $dto = ClientIdDTO::fromUuid($uuid);

    $repository = Mockery::mock(ClientRepositoryInterface::class);
    $repository->shouldReceive('deleteByUuid')
        ->with($uuid)
        ->once()
        ->andThrow(new RuntimeException('Client not found'));

    $useCase = new DeleteClientUseCase($repository);

    expect(fn () => $useCase->execute($dto))
        ->toThrow(RuntimeException::class);
});

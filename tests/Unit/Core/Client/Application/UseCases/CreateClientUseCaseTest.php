<?php

declare(strict_types=1);

use App\Core\Client\Application\DTOs\CreateClientDTO;
use App\Core\Client\Application\UseCases\CreateClientUseCase;
use App\Core\Client\Domain\Entity\Client;
use App\Core\Client\Domain\Repositories\ClientRepositoryInterface;
use App\Http\Requests\Client\StoreClientRequest;

it('creates a client successfully', function () {
    $request = Mockery::mock(StoreClientRequest::class);

    $request->shouldReceive('input')
        ->with('name')
        ->andReturn('John Doe');

    $dto = CreateClientDTO::fromRequest($request);

    $repository = Mockery::mock(ClientRepositoryInterface::class);

    $expectedClient = Client::restore('John Doe');

    $repository->shouldReceive('save')
        ->once()
        ->with(Mockery::on(static function (Client $client) {
            return $client->name === 'John Doe';
        }))
        ->andReturn($expectedClient);

    $useCase = new CreateClientUseCase($repository);
    $result = $useCase->execute($dto);

    expect($result)->toBe($expectedClient)
        ->and($result->name)->toBe('John Doe');
});

<?php

declare(strict_types=1);

use App\Core\ApiAuth\Application\DTOs\AuthenticatedUserDTO;
use App\Core\ApiAuth\Application\UseCases\GetAuthenticatedUserUseCase;
use App\Core\ApiAuth\Domain\Entity\AuthenticatedUser;
use App\Core\ApiAuth\Domain\Services\TokenAuthServiceInterface;

beforeEach(function () {
    $this->tokenService = Mockery::mock(TokenAuthServiceInterface::class);
    $this->useCase = new GetAuthenticatedUserUseCase($this->tokenService);
});

it('returns the authenticated user data', function () {
    $this->tokenService->shouldReceive('authenticatedUser')
        ->once()
        ->andReturn(new AuthenticatedUser(id: 42, name: 'John Doe', email: 'john@example.com'));

    $result = $this->useCase->execute();

    expect($result)->toBeInstanceOf(AuthenticatedUserDTO::class)
        ->and($result->toArray())->toBe([
            'id' => 42,
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);
});

it('returns null when there is no authenticated user', function () {
    $this->tokenService->shouldReceive('authenticatedUser')
        ->once()
        ->andReturnNull();

    expect($this->useCase->execute())->toBeNull();
});

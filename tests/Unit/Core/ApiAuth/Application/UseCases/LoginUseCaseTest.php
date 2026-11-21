<?php

declare(strict_types=1);

use App\Core\ApiAuth\Application\DTOs\LoginDTO;
use App\Core\ApiAuth\Application\DTOs\TokenDTO;
use App\Core\ApiAuth\Application\UseCases\LoginUseCase;
use App\Core\ApiAuth\Domain\Services\TokenAuthServiceInterface;

beforeEach(function () {
    $this->tokenService = Mockery::mock(TokenAuthServiceInterface::class);
    $this->useCase = new LoginUseCase($this->tokenService);
    $this->dto = new LoginDTO(email: 'john@example.com', password: 'secret123');
});

it('returns a bearer token when credentials are valid', function () {
    $this->tokenService->shouldReceive('attempt')
        ->with('john@example.com', 'secret123')
        ->once()
        ->andReturn('jwt-token');

    $this->tokenService->shouldReceive('tokenTimeToLive')
        ->once()
        ->andReturn(3600);

    $result = $this->useCase->execute($this->dto);

    expect($result)->toBeInstanceOf(TokenDTO::class)
        ->and($result->toArray())->toBe([
            'token' => 'jwt-token',
            'token_type' => 'Bearer',
            'expires_in' => 3600,
        ]);
});

it('returns null when credentials are invalid', function () {
    $this->tokenService->shouldReceive('attempt')
        ->with('john@example.com', 'secret123')
        ->once()
        ->andReturnNull();

    $this->tokenService->shouldNotReceive('tokenTimeToLive');

    expect($this->useCase->execute($this->dto))->toBeNull();
});

<?php

declare(strict_types=1);

use App\Core\ApiAuth\Application\DTOs\TokenDTO;
use App\Core\ApiAuth\Application\UseCases\RefreshTokenUseCase;
use App\Core\ApiAuth\Domain\Services\TokenAuthServiceInterface;

beforeEach(function () {
    $this->tokenService = Mockery::mock(TokenAuthServiceInterface::class);
    $this->useCase = new RefreshTokenUseCase($this->tokenService);
});

it('returns a new bearer token when the current token can be refreshed', function () {
    $this->tokenService->shouldReceive('refreshCurrentToken')
        ->once()
        ->andReturn('refreshed-jwt-token');

    $this->tokenService->shouldReceive('tokenTimeToLive')
        ->once()
        ->andReturn(7200);

    $result = $this->useCase->execute();

    expect($result)->toBeInstanceOf(TokenDTO::class)
        ->and($result->toArray())->toBe([
            'token' => 'refreshed-jwt-token',
            'token_type' => 'Bearer',
            'expires_in' => 7200,
        ]);
});

it('returns null when the current token cannot be refreshed', function () {
    $this->tokenService->shouldReceive('refreshCurrentToken')
        ->once()
        ->andReturnNull();

    $this->tokenService->shouldNotReceive('tokenTimeToLive');

    expect($this->useCase->execute())->toBeNull();
});

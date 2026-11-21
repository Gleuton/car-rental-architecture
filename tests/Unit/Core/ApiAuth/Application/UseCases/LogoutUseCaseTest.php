<?php

declare(strict_types=1);

use App\Core\ApiAuth\Application\UseCases\LogoutUseCase;
use App\Core\ApiAuth\Domain\Services\TokenAuthServiceInterface;

beforeEach(function () {
    $this->tokenService = Mockery::mock(TokenAuthServiceInterface::class);
    $this->useCase = new LogoutUseCase($this->tokenService);
});

it('returns the result of invalidating the current token', function (bool $invalidated) {
    $this->tokenService->shouldReceive('invalidateCurrentToken')
        ->once()
        ->andReturn($invalidated);

    expect($this->useCase->execute())->toBe($invalidated);
})->with([
    'token invalidated' => true,
    'token not invalidated' => false,
]);

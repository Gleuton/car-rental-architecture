<?php

declare(strict_types=1);

namespace App\Core\ApiAuth\Application\UseCases;

use App\Core\ApiAuth\Application\DTOs\TokenDTO;
use App\Core\ApiAuth\Domain\Services\TokenAuthServiceInterface;

readonly class RefreshTokenUseCase
{
    public function __construct(
        private TokenAuthServiceInterface $tokenService,
    ) {}

    public function execute(): ?TokenDTO
    {
        $token = $this->tokenService->refreshCurrentToken();

        if ($token === null) {
            return null;
        }

        return new TokenDTO(
            accessToken: $token,
            tokenType: 'Bearer',
            expiresIn: $this->tokenService->tokenTimeToLive(),
        );
    }
}

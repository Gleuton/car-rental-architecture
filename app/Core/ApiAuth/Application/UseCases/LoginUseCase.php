<?php

declare(strict_types=1);

namespace App\Core\ApiAuth\Application\UseCases;

use App\Core\ApiAuth\Application\DTOs\LoginDTO;
use App\Core\ApiAuth\Application\DTOs\TokenDTO;
use App\Core\ApiAuth\Domain\Services\TokenAuthServiceInterface;

readonly class LoginUseCase
{
    public function __construct(
        private TokenAuthServiceInterface $tokenService
    ) {}

    public function execute(LoginDTO $dto): ?TokenDTO
    {
        $token = $this->tokenService->attempt($dto->email, $dto->password);

        if ($token === null) {
            return null;
        }

        return new TokenDTO(
            accessToken: $token,
            tokenType: 'Bearer',
            expiresIn: $this->tokenService->tokenTimeToLive()
        );
    }
}

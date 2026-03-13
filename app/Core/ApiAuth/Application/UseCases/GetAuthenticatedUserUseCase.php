<?php

declare(strict_types=1);

namespace App\Core\ApiAuth\Application\UseCases;

use App\Core\ApiAuth\Application\DTOs\AuthenticatedUserDTO;
use App\Core\ApiAuth\Domain\Services\TokenAuthServiceInterface;

readonly class GetAuthenticatedUserUseCase
{
    public function __construct(
        private TokenAuthServiceInterface $tokenService,
    ) {}

    public function execute(): ?AuthenticatedUserDTO
    {
        $user = $this->tokenService->authenticatedUser();

        if ($user === null) {
            return null;
        }

        return new AuthenticatedUserDTO(
            id: (int) $user->getAuthIdentifier(),
            name: (string) data_get($user, 'name', ''),
            email: (string) data_get($user, 'email', ''),
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Core\ApiAuth\Application\UseCases;

use App\Core\ApiAuth\Domain\Services\TokenAuthServiceInterface;

readonly class LogoutUseCase
{
    public function __construct(
        private TokenAuthServiceInterface $tokenService,
    ) {}

    public function execute(): bool
    {
        return $this->tokenService->invalidateCurrentToken();
    }
}

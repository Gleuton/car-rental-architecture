<?php

declare(strict_types=1);

namespace App\Core\ApiAuth\Domain\Services;

use App\Core\ApiAuth\Domain\Entity\AuthenticatedUser;

interface TokenAuthServiceInterface
{
    public function attempt(string $email, string $password): ?string;

    public function refreshCurrentToken(): ?string;

    public function tokenTimeToLive(): int;

    public function authenticatedUser(): ?AuthenticatedUser;

    public function invalidateCurrentToken(): bool;
}

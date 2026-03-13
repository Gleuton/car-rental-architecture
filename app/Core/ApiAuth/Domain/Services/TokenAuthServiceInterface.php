<?php

declare(strict_types=1);

namespace App\Core\ApiAuth\Domain\Services;

use Illuminate\Contracts\Auth\Authenticatable;

interface TokenAuthServiceInterface
{
    public function attempt(string $email, string $password): ?string;

    public function tokenTimeToLive(): int;

    public function authenticatedUser(): ?Authenticatable;

    public function invalidateCurrentToken(): bool;
}

<?php

declare(strict_types=1);

namespace App\Core\ApiAuth\Domain\Services;

interface TokenAuthServiceInterface
{
    public function attempt(string $email, string $password): ?string;

    public function tokenTimeToLive(): int;
}

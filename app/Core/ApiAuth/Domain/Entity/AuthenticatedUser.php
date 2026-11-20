<?php

declare(strict_types=1);

namespace App\Core\ApiAuth\Domain\Entity;

final readonly class AuthenticatedUser
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
    ) {}
}

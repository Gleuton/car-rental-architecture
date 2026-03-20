<?php

declare(strict_types=1);

namespace App\Core\ApiAuth\Application\DTOs;

readonly class TokenDTO
{
    public function __construct(
        public string $accessToken,
        public string $tokenType,
        public int $expiresIn
    ) {}

    /**
     * @return array{token: string, token_type: string, expires_in: int}
     */
    public function toArray(): array
    {
        return [
            'token' => $this->accessToken,
            'token_type' => $this->tokenType,
            'expires_in' => $this->expiresIn,
        ];
    }
}

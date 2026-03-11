<?php

declare(strict_types=1);

namespace App\Core\ApiAuth\Application\DTOs;

use App\Http\Requests\AuthApi\LoginRequest;

readonly class LoginDTO
{
    public function __construct(
        public string $email,
        public string $password
    ) {}

    public static function fromRequest(LoginRequest $request): self
    {
        return new self(
            email: (string) $request->string('email'),
            password: (string) $request->string('password')
        );
    }
}

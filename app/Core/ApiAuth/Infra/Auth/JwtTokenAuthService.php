<?php

declare(strict_types=1);

namespace App\Core\ApiAuth\Infra\Auth;

use App\Core\ApiAuth\Domain\Services\TokenAuthServiceInterface;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

class JwtTokenAuthService implements TokenAuthServiceInterface
{
    public function attempt(string $email, string $password): ?string
    {
        $token = Auth::guard('api')->attempt([
            'email' => $email,
            'password' => $password,
        ]);

        if (! is_string($token) || $token === '') {
            return null;
        }

        return $token;
    }

    public function tokenTimeToLive(): int
    {
        return (int) config('jwt.ttl', 60) * 60;
    }

    public function authenticatedUser(): ?Authenticatable
    {
        $user = Auth::guard('api')->user();

        return $user instanceof Authenticatable ? $user : null;
    }
}

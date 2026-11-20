<?php

declare(strict_types=1);

namespace App\Core\ApiAuth\Infra\Auth;

use App\Core\ApiAuth\Domain\Entity\AuthenticatedUser;
use App\Core\ApiAuth\Domain\Services\TokenAuthServiceInterface;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Throwable;
use Tymon\JWTAuth\JWTGuard;

class JwtTokenAuthService implements TokenAuthServiceInterface
{
    public function attempt(string $email, string $password): ?string
    {
        $guard = $this->jwtGuard();

        if ($guard === null) {
            return null;
        }

        $token = $guard->attempt([
            'email' => $email,
            'password' => $password,
        ]);

        if (! is_string($token) || $token === '') {
            return null;
        }

        return $token;
    }

    public function refreshCurrentToken(): ?string
    {
        $guard = $this->jwtGuard();

        if ($guard === null) {
            return null;
        }

        try {
            $token = $guard->refresh();
        } catch (Throwable) {
            return null;
        }

        return is_string($token) && $token !== '' ? $token : null;
    }

    public function tokenTimeToLive(): int
    {
        return (int) config('jwt.ttl', 60) * 60;
    }

    public function authenticatedUser(): ?AuthenticatedUser
    {
        $user = $this->jwtGuard()?->user();

        if (! $user instanceof User) {
            return null;
        }

        return new AuthenticatedUser(
            id: (int) $user->getKey(),
            name: $user->name,
            email: $user->email,
        );
    }

    public function invalidateCurrentToken(): bool
    {
        $guard = $this->jwtGuard();

        if ($guard === null || $guard->user() === null) {
            return false;
        }

        try {
            $guard->logout();

            return true;
        } catch (Throwable) {
            return false;
        }
    }

    private function jwtGuard(): ?JWTGuard
    {
        $guard = Auth::guard('api');

        return $guard instanceof JWTGuard ? $guard : null;
    }
}

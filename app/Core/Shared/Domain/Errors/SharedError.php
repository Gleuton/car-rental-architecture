<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\Errors;

enum SharedError: int
{
    case INVALID_UUID = 1001;

    public function message(): string
    {
        return match ($this) {
            self::INVALID_UUID => 'Invalid UUID format',
        };
    }
}

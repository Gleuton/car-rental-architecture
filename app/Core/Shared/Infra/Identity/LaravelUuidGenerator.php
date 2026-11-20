<?php

declare(strict_types=1);

namespace App\Core\Shared\Infra\Identity;

use App\Core\Shared\Domain\Exceptions\SharedDomainException;
use App\Core\Shared\Domain\Identity\UuidGeneratorInterface;
use App\Core\Shared\Domain\ValueObjects\Uuid;
use Illuminate\Support\Str;

final class LaravelUuidGenerator implements UuidGeneratorInterface
{
    /**
     * @throws SharedDomainException
     */
    public function generate(): Uuid
    {
        return Uuid::fromString((string) Str::uuid());
    }
}

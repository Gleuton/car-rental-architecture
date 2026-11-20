<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\ValueObjects;

use App\Core\Shared\Domain\Errors\SharedError;
use App\Core\Shared\Domain\Exceptions\SharedDomainException;
use Stringable;

final readonly class Uuid implements Stringable
{
    private const string FORMAT_PATTERN = '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/iD';

    private function __construct(public string $value) {}

    /**
     * @throws SharedDomainException
     */
    public static function fromString(string $value): self
    {
        if (preg_match(self::FORMAT_PATTERN, $value) !== 1) {
            throw new SharedDomainException(SharedError::INVALID_UUID);
        }

        return new self(strtolower($value));
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}

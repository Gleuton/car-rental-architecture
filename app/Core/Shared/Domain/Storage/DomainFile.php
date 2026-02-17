<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\Storage;

final class DomainFile
{
    public function __construct(
        public readonly string $name,
        public readonly string $mimeType,
        public readonly string $content
    ) {}

    public static function fromBinary(
        string $name,
        string $mimeType,
        string $content
    ): self {
        return new self($name, $mimeType, $content);
    }
}

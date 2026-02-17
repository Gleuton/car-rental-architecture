<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\Storage;

final readonly class StoredFile
{
    public function __construct(
        public string $path,
        public string $url
    ) {}
}

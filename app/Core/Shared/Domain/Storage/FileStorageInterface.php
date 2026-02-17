<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\Storage;

interface FileStorageInterface
{
    public function upload(DomainFile $file, string $path): StoredFile;

    public function delete(string $path): bool;

    public function getUrl(string $path): string;
}

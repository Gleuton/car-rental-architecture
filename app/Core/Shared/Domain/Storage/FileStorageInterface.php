<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\Storage;

use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;

interface FileStorageInterface
{
    public function upload(UploadedFile|File $file, string $path): string|bool;

    public function delete(string $path): bool;

    public function getUrl(string $path): string;
}

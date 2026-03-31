<?php

declare(strict_types=1);

namespace App\Core\Shared\Infra\Adapters;

use App\Core\Shared\Domain\Storage\DomainFile;
use Illuminate\Http\UploadedFile;

final class LaravelUploadedFileAdapter
{
    public static function adapt(UploadedFile $file, ?string $fileName = null): DomainFile
    {
        return new DomainFile(
            name: $fileName ?? $file->getClientOriginalName(),
            mimeType: $file->getClientMimeType(),
            content: file_get_contents($file->getRealPath())
        );
    }
}

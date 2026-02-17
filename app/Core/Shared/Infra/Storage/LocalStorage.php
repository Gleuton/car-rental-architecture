<?php

declare(strict_types=1);

namespace App\Core\Shared\Infra\Storage;

use App\Core\Shared\Domain\Storage\DomainFile;
use App\Core\Shared\Domain\Storage\FileStorageInterface;
use App\Core\Shared\Domain\Storage\StoredFile;
use Illuminate\Contracts\Filesystem\Cloud;
use Illuminate\Support\Facades\Storage;

class LocalStorage implements FileStorageInterface
{
    private string $disk = 'public';

    public function upload(DomainFile $file, string $path): StoredFile
    {
        $fullPath = trim($path, '/').'/'.$file->name;

        Storage::disk($this->disk)->put($fullPath, $file->content);

        return new StoredFile(
            path: $fullPath,
            url: Storage::url($fullPath)
        );
    }

    public function delete(string $path): bool
    {
        if (Storage::disk($this->disk)->exists($path)) {
            return Storage::disk($this->disk)->delete($path);
        }

        return false;
    }

    public function getUrl(string $path): string
    {
        /**
         * @var Cloud $storage
         */
        $storage = Storage::disk($this->disk);

        return $storage->url($path);
    }
}

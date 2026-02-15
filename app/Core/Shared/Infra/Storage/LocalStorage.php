<?php

namespace App\Core\Shared\Infra\Storage;

use App\Core\Shared\Domain\Storage\FileStorageInterface;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class LocalStorage implements FileStorageInterface
{
    private string $disk = 'public';

    public function upload(UploadedFile|File $file, string $path): string
    {
        return Storage::disk($this->disk)->put($path, $file);
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
        return Storage::disk($this->disk)->url($path);
    }
}

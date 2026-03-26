<?php

declare(strict_types=1);

namespace App\Core\Brand\Application\Services;

use App\Core\Shared\Domain\Storage\FileStorageInterface;
use App\Core\Shared\Infra\Adapters\LaravelUploadedFileAdapter;
use Illuminate\Http\UploadedFile;

class BrandLogoService
{
    public function __construct(
        private FileStorageInterface $storage
    ) {}

    public function upload(UploadedFile $file): string
    {
        $domainFile = LaravelUploadedFileAdapter::adapt($file);

        return $this->storage->upload($domainFile, 'brands')->path;
    }

    public function delete(string $path): void
    {
        $this->storage->delete($path);
    }

    public function replace(UploadedFile $newFile, string $oldPath): string
    {
        $newPath = $this->upload($newFile);
        $this->delete($oldPath);

        return $newPath;
    }
}

<?php

declare(strict_types=1);

namespace App\Core\Car\Application\Services;

use App\Core\Shared\Domain\Storage\FileStorageInterface;
use App\Core\Shared\Infra\Adapters\LaravelUploadedFileAdapter;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class BrandLogoService
{
    public function __construct(
        private FileStorageInterface $storage
    ) {}

    public function upload(UploadedFile $file, string $brandName): string
    {
        $fileName = $this->generateFileName($file, $brandName);
        $domainFile = LaravelUploadedFileAdapter::adapt($file, $fileName);

        return $this->storage->upload($domainFile, 'brands')->path;
    }

    public function delete(string $path): void
    {
        $this->storage->delete($path);
    }

    public function replace(UploadedFile $newFile, string $oldPath, string $brandName): string
    {
        $newPath = $this->upload($newFile, $brandName);
        $this->delete($oldPath);

        return $newPath;
    }

    private function generateFileName(UploadedFile $file, string $brandName): string
    {
        $timestamp = now()->format('YmdHis');
        $brandSlug = Str::slug($brandName, '-');
        $randomSuffix = Str::lower(Str::random(6));
        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'bin');

        return "{$timestamp}-{$brandSlug}-{$randomSuffix}.{$extension}";
    }
}

<?php

declare(strict_types=1);

use App\Core\Brand\Application\Services\BrandLogoService;
use App\Core\Shared\Domain\Storage\FileStorageInterface;
use App\Core\Shared\Domain\Storage\StoredFile;
use Illuminate\Http\UploadedFile;

it('uploads a logo successfully', function () {
    $file = UploadedFile::fake()->create('fiat.png', 100);

    $storage = Mockery::mock(FileStorageInterface::class);
    $storedFile = new StoredFile('brands/fiat_stored.png', '');

    $storage->shouldReceive('upload')
        ->once()
        ->andReturn($storedFile);

    $service = new BrandLogoService($storage);
    $result = $service->upload($file);

    expect($result)->toBe('brands/fiat_stored.png');
});

it('deletes a logo successfully', function () {
    $storage = Mockery::mock(FileStorageInterface::class);
    $storage->shouldReceive('delete')
        ->with('brands/fiat.png')
        ->once();

    $service = new BrandLogoService($storage);
    $service->delete('brands/fiat.png');

    expect(true)->toBeTrue();
});

it('replaces a logo successfully', function () {
    $newFile = UploadedFile::fake()->create('fiat_new.png', 100);

    $storage = Mockery::mock(FileStorageInterface::class);
    $storedFile = new StoredFile('brands/fiat_new.png', '');

    $storage->shouldReceive('upload')
        ->once()
        ->andReturn($storedFile);

    $storage->shouldReceive('delete')
        ->with('brands/fiat_old.png')
        ->once();

    $service = new BrandLogoService($storage);
    $result = $service->replace($newFile, 'brands/fiat_old.png');

    expect($result)->toBe('brands/fiat_new.png');
});

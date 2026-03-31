<?php

declare(strict_types=1);

use App\Core\Brand\Application\Services\BrandLogoService;
use App\Core\Shared\Domain\Storage\DomainFile;
use App\Core\Shared\Domain\Storage\FileStorageInterface;
use App\Core\Shared\Domain\Storage\StoredFile;
use Illuminate\Http\UploadedFile;

it('uploads a logo successfully', function () {
    $file = UploadedFile::fake()->create('fiat.png', 100);

    $storage = Mockery::mock(FileStorageInterface::class);
    $storedFile = new StoredFile('brands/generated.png', '');

    $storage->shouldReceive('upload')
        ->with(
            Mockery::on(function (DomainFile $domainFile) {
                expect($domainFile->name)
                    ->toMatch('/^\d{14}-fiat-[a-z0-9]{6}\.png$/');

                return true;
            }),
            'brands'
        )
        ->once()
        ->andReturn($storedFile);

    $service = new BrandLogoService($storage);
    $result = $service->upload($file, 'Fiat');

    expect($result)->toBe('brands/generated.png');
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
    $storedFile = new StoredFile('brands/generated.png', '');

    $storage->shouldReceive('upload')
        ->with(
            Mockery::on(function (DomainFile $domainFile) {
                expect($domainFile->name)
                    ->toMatch('/^\d{14}-fiat-[a-z0-9]{6}\.png$/');

                return true;
            }),
            'brands'
        )
        ->once()
        ->andReturn($storedFile);

    $storage->shouldReceive('delete')
        ->with('brands/fiat_old.png')
        ->once();

    $service = new BrandLogoService($storage);
    $result = $service->replace($newFile, 'brands/fiat_old.png', 'Fiat');

    expect($result)->toBe('brands/generated.png');
});

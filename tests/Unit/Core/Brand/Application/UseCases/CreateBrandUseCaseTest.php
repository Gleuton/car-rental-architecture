<?php

declare(strict_types=1);

use App\Core\Brand\Application\DTOs\CreateBrandDTO;
use App\Core\Brand\Application\UseCases\CreateBrandUseCase;
use App\Core\Brand\Domain\Entity\Brand as DomainBrand;
use App\Core\Brand\Domain\Exceptions\BrandDomainException;
use App\Core\Brand\Domain\Repositories\BrandRepositoryInterface;
use App\Core\Brand\Domain\Roles\UniqueBrandNameRule;
use App\Core\Shared\Domain\Storage\DomainFile;
use App\Core\Shared\Domain\Storage\FileStorageInterface;
use App\Core\Shared\Domain\Storage\StoredFile;
use App\Http\Requests\Brand\StoreBrandRequest;
use Illuminate\Http\UploadedFile;

beforeEach(function () {
    $this->repository = Mockery::mock(BrandRepositoryInterface::class);
    $this->uniqueRule = new UniqueBrandNameRule($this->repository);
    $this->storage = Mockery::mock(FileStorageInterface::class);

    $this->useCase = new CreateBrandUseCase(
        $this->repository,
        $this->uniqueRule,
        $this->storage
    );
});

it('creates a brand successfully when name is unique', function () {
    $file = UploadedFile::fake()->create('fiat.png', 100);
    $request = Mockery::mock(StoreBrandRequest::class);

    $request->shouldReceive('file')
        ->with('image')
        ->andReturn($file);

    $request->shouldReceive('input')
        ->with('name')
        ->andReturn('Fiat');

    $dto = CreateBrandDTO::fromRequest($request);

    $this->repository->shouldReceive('existsByName')
        ->with('Fiat')
        ->once()
        ->andReturn(false);

    $storedFile = new StoredFile('brands/fiat_stored.png', '');

    $this->storage->shouldReceive('upload')
        ->with(
            Mockery::type(DomainFile::class),
            'brands'
        )
        ->once()
        ->andReturn($storedFile);

    $expectedBrand = DomainBrand::restore(
        1,
        'Fiat',
        'brands/fiat_stored.png'
    );

    $this->repository->shouldReceive('save')
        ->once()
        ->andReturn($expectedBrand);

    $result = $this->useCase->execute($dto);

    expect($result->id)->toBe(1)
        ->and($result->name)->toBe('Fiat')
        ->and($result->image)->toBe('brands/fiat_stored.png');
});

it('throws exception when brand name already exists', function () {
    $file = UploadedFile::fake()->create('fiat.png', 100);
    $request = Mockery::mock(StoreBrandRequest::class);

    $request->shouldReceive('file')->with('image')->andReturn($file);
    $request->shouldReceive('input')->with('name')->andReturn('Fiat');
    $dto = CreateBrandDTO::fromRequest($request);

    $this->repository->shouldReceive('existsByName')->with('Fiat')->once()->andReturn(true);

    expect(fn () => $this->useCase->execute($dto))
        ->toThrow(BrandDomainException::class);
});

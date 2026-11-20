<?php

declare(strict_types=1);

use App\Core\Car\Application\DTOs\Brand\CreateBrandDTO;
use App\Core\Car\Application\Services\BrandLogoService;
use App\Core\Car\Application\UseCases\Brand\CreateBrandUseCase;
use App\Core\Car\Domain\Entity\Brand as DomainBrand;
use App\Core\Car\Domain\Exceptions\BrandDomainException;
use App\Core\Car\Domain\Repositories\BrandRepositoryInterface;
use App\Core\Car\Domain\Roles\UniqueBrandNameRule;
use App\Core\Shared\Domain\Identity\UuidGeneratorInterface;
use App\Core\Shared\Domain\ValueObjects\Uuid;
use App\Http\Requests\Brand\StoreBrandRequest;
use Illuminate\Http\UploadedFile;

const TEST_CREATED_BRAND_UUID = 'dddddddd-dddd-4ddd-8ddd-dddddddddddd';

beforeEach(function () {
    $this->repository = Mockery::mock(BrandRepositoryInterface::class);
    $this->uniqueRule = new UniqueBrandNameRule($this->repository);
    $this->logoService = Mockery::mock(BrandLogoService::class);
    $this->uuidGenerator = Mockery::mock(UuidGeneratorInterface::class);

    $this->useCase = new CreateBrandUseCase(
        $this->repository,
        $this->uniqueRule,
        $this->logoService,
        $this->uuidGenerator,
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

    $this->logoService->shouldReceive('upload')
        ->with($file, 'Fiat')
        ->once()
        ->andReturn('brands/fiat_stored.png');

    $this->uuidGenerator->shouldReceive('generate')
        ->once()
        ->andReturn(Uuid::fromString(TEST_CREATED_BRAND_UUID));

    $this->repository->shouldReceive('save')
        ->once()
        ->with(Mockery::on(static fn (DomainBrand $brand): bool => $brand->uuid() === TEST_CREATED_BRAND_UUID
            && $brand->name() === 'Fiat'
            && $brand->imagePath() === 'brands/fiat_stored.png'))
        ->andReturnUsing(static fn (DomainBrand $brand): DomainBrand => $brand);

    $result = $this->useCase->execute($dto);

    expect($result->name())->toBe('Fiat')
        ->and($result->uuid())->toBe(TEST_CREATED_BRAND_UUID)
        ->and($result->imagePath())->toBe('brands/fiat_stored.png');
});

it('throws exception when brand name already exists', function () {
    $file = UploadedFile::fake()->create('fiat.png', 100);
    $request = Mockery::mock(StoreBrandRequest::class);

    $request->shouldReceive('file')->with('image')->andReturn($file);
    $request->shouldReceive('input')->with('name')->andReturn('Fiat');
    $dto = CreateBrandDTO::fromRequest($request);

    $this->repository->shouldReceive('existsByName')->with('Fiat')->once()->andReturn(true);
    $this->uuidGenerator->shouldNotReceive('generate');
    $this->repository->shouldNotReceive('save');

    expect(fn () => $this->useCase->execute($dto))
        ->toThrow(BrandDomainException::class);
});

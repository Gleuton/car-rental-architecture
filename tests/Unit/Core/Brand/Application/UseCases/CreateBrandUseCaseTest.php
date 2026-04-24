<?php

declare(strict_types=1);

use App\Core\Brand\Domain\Exceptions\BrandDomainException;
use App\Core\Brand\Domain\Repositories\BrandRepositoryInterface;
use App\Core\Brand\Domain\Roles\UniqueBrandNameRule;
use App\Core\Car\Application\DTOs\Brand\CreateBrandDTO;
use App\Core\Car\Application\Services\BrandLogoService;
use App\Core\Car\Application\UseCases\Brand\CreateBrandUseCase;
use App\Core\Car\Domain\Entities\Brand as DomainBrand;
use App\Http\Requests\Brand\StoreBrandRequest;
use Illuminate\Http\UploadedFile;

beforeEach(function () {
    $this->repository = Mockery::mock(BrandRepositoryInterface::class);
    $this->uniqueRule = new UniqueBrandNameRule($this->repository);
    $this->logoService = Mockery::mock(BrandLogoService::class);

    $this->useCase = new CreateBrandUseCase(
        $this->repository,
        $this->uniqueRule,
        $this->logoService
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

    $expectedBrand = DomainBrand::create(
        'Fiat',
        'brands/fiat_stored.png',
        '11111111-1111-4111-8111-111111111111'
    );

    $this->repository->shouldReceive('save')
        ->once()
        ->andReturn($expectedBrand);

    $result = $this->useCase->execute($dto);

    expect($result->name())->toBe('Fiat')
        ->and($result->uuid())->toBe($expectedBrand->uuid())
        ->and($result->imagePath())->toBe('brands/fiat_stored.png');
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

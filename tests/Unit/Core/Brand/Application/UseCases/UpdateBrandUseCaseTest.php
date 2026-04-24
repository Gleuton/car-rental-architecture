<?php

declare(strict_types=1);

use App\Core\Brand\Application\UseCases\UpdateBrandUseCase;
use App\Core\Brand\Domain\Entity\Brand;
use App\Core\Brand\Domain\Errors\BrandError;
use App\Core\Brand\Domain\Exceptions\BrandDomainException;
use App\Core\Brand\Domain\Repositories\BrandRepositoryInterface;
use App\Core\Brand\Domain\Roles\UniqueBrandNameRule;
use App\Core\Car\Application\DTOs\Brand\UpdateBrandDTO;
use App\Core\Car\Application\Services\BrandLogoService;
use App\Http\Requests\Brand\UpdateBrandRequest;
use Illuminate\Http\UploadedFile;

beforeEach(function () {
    $this->brandUuid = '11111111-1111-4111-8111-111111111111';
    $this->repository = Mockery::mock(BrandRepositoryInterface::class);
    $this->logoService = Mockery::mock(BrandLogoService::class);
    $this->uniqueRule = Mockery::mock(UniqueBrandNameRule::class);

    $this->useCase = new UpdateBrandUseCase(
        $this->repository,
        $this->uniqueRule,
        $this->logoService
    );

    $this->existingBrand = Brand::create('Fiat', 'brands/fiat.png', $this->brandUuid);
});

it('updates a brand with name and image successfully', function () {
    $file = UploadedFile::fake()->create('fiat_updated.png', 100);
    $requestMock = Mockery::mock(UpdateBrandRequest::class);

    $requestMock->shouldReceive('file')->with('image')->andReturn($file);
    $requestMock->shouldReceive('input')->with('name')->andReturn('Fiat Updated');

    $dto = UpdateBrandDTO::fromRequestUuid($requestMock, $this->brandUuid);

    $this->uniqueRule->shouldReceive('validate')->with('Fiat Updated')->once();

    $this->repository->shouldReceive('findByUuid')
        ->with($this->brandUuid)
        ->once()
        ->andReturn($this->existingBrand);

    $this->logoService->shouldReceive('replace')
        ->with($file, 'brands/fiat.png', 'Fiat Updated')
        ->once()
        ->andReturn('brands/fiat_updated.png');

    $updatedBrand = Brand::create('Fiat Updated', 'brands/fiat_updated.png', $this->brandUuid);

    $this->repository->shouldReceive('update')
        ->with(Mockery::type(Brand::class))
        ->once()
        ->andReturn($updatedBrand);

    $result = $this->useCase->execute($dto);

    expect($result->name())->toBe('Fiat Updated')
        ->and($result->imagePath())->toBe('brands/fiat_updated.png');
});

it('updates a brand name only without changing image', function () {
    $requestMock = Mockery::mock(UpdateBrandRequest::class);

    $requestMock->shouldReceive('file')->with('image')->andReturn(null);
    $requestMock->shouldReceive('input')->with('name')->andReturn('Toyota');

    $dto = UpdateBrandDTO::fromRequestUuid($requestMock, $this->brandUuid);

    $this->uniqueRule->shouldReceive('validate')->with('Toyota')->once();

    $this->repository->shouldReceive('findByUuid')
        ->with($this->brandUuid)
        ->once()
        ->andReturn($this->existingBrand);

    $this->logoService->shouldNotReceive('replace');

    $updatedBrand = Brand::create('Toyota', 'brands/fiat.png', $this->brandUuid);

    $this->repository->shouldReceive('update')
        ->with(Mockery::type(Brand::class))
        ->once()
        ->andReturn($updatedBrand);

    $result = $this->useCase->execute($dto);

    expect($result->name())->toBe('Toyota')
        ->and($result->imagePath())->toBe('brands/fiat.png');
});

it('updates a brand image only without validating name uniqueness', function () {
    $file = UploadedFile::fake()->create('fiat_new.png', 100);
    $requestMock = Mockery::mock(UpdateBrandRequest::class);

    $requestMock->shouldReceive('file')->with('image')->andReturn($file);
    $requestMock->shouldReceive('input')->with('name')->andReturn(null);

    $dto = UpdateBrandDTO::fromRequestUuid($requestMock, $this->brandUuid);

    $this->uniqueRule->shouldNotReceive('validate');

    $this->repository->shouldReceive('findByUuid')
        ->with($this->brandUuid)
        ->once()
        ->andReturn($this->existingBrand);

    $this->logoService->shouldReceive('replace')
        ->with($file, 'brands/fiat.png', 'Fiat')
        ->once()
        ->andReturn('brands/fiat_new.png');

    $updatedBrand = Brand::create('Fiat', 'brands/fiat_new.png', $this->brandUuid);

    $this->repository->shouldReceive('update')
        ->with(Mockery::type(Brand::class))
        ->once()
        ->andReturn($updatedBrand);

    $result = $this->useCase->execute($dto);

    expect($result->name())->toBe('Fiat')
        ->and($result->imagePath())->toBe('brands/fiat_new.png');
});

it('throws exception when updating brand with duplicate name', function () {
    $requestMock = Mockery::mock(UpdateBrandRequest::class);

    $requestMock->shouldReceive('file')->with('image')->andReturn(null);
    $requestMock->shouldReceive('input')->with('name')->andReturn('Toyota');

    $dto = UpdateBrandDTO::fromRequestUuid($requestMock, $this->brandUuid);

    $this->repository->shouldReceive('findByUuid')
        ->with($this->brandUuid)
        ->once()
        ->andReturn($this->existingBrand);

    $this->uniqueRule->shouldReceive('validate')
        ->with('Toyota')
        ->once()
        ->andThrow(new BrandDomainException(BrandError::ALREADY_EXISTS));

    expect(fn () => $this->useCase->execute($dto))
        ->toThrow(BrandDomainException::class, 'Brand already exists');
});

it('propagates exception when brand is not found during update', function () {
    $requestMock = Mockery::mock(UpdateBrandRequest::class);

    $requestMock->shouldReceive('file')->with('image')->andReturn(null);
    $requestMock->shouldReceive('input')->with('name')->andReturn(null);

    $dto = UpdateBrandDTO::fromRequestUuid($requestMock, '99999999-9999-4999-8999-999999999999');

    $this->repository->shouldReceive('findByUuid')
        ->with('99999999-9999-4999-8999-999999999999')
        ->once()
        ->andThrow(new RuntimeException('Brand not found'));

    expect(fn () => $this->useCase->execute($dto))
        ->toThrow(RuntimeException::class);
});

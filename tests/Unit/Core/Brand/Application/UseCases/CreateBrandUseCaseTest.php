<?php
use App\Core\Brand\Application\UseCases\CreateBrandUseCase;
use App\Core\Brand\Application\DTOs\CreateBrandDTO;
use App\Core\Brand\Domain\Repositories\BrandRepositoryInterface;
use App\Core\Brand\Domain\Roles\UniqueBrandNameRule;
use App\Core\Brand\Domain\Entity\Brand as DomainBrand;
use App\Core\Brand\Domain\Exceptions\BrandDomainException;
use App\Http\Requests\Brand\StoreBrandRequest;

beforeEach(function () {
    $this->repository = Mockery::mock(BrandRepositoryInterface::class);
    $this->uniqueRule = new UniqueBrandNameRule($this->repository);
    $this->useCase = new CreateBrandUseCase($this->repository, $this->uniqueRule);
});

it('creates a brand successfully when name is unique', function () {
    $request = Mockery::mock(StoreBrandRequest::class);
    $request->name = 'Fiat';
    $request->image = 'fiat.png';

    $dto = CreateBrandDTO::fromRequest($request);

    $this->repository->shouldReceive('existsByName')->with('Fiat')->once()->andReturn(false);

    $expectedBrand = DomainBrand::createWithId(1, 'Fiat', 'fiat.png');
    $this->repository->shouldReceive('save')->once()->andReturn($expectedBrand);

    $result = $this->useCase->execute($dto);

    expect($result->id)->toBe(1)
        ->and($result->name)->toBe('Fiat')
        ->and($result->image)->toBe('fiat.png');
});

it('throws exception when brand name already exists', function () {
    $request = Mockery::mock(StoreBrandRequest::class);
    $request->name = 'Fiat';
    $request->image = 'fiat.png';
    $dto = CreateBrandDTO::fromRequest($request);

    $this->repository->shouldReceive('existsByName')->with('Fiat')->once()->andReturn(true);

    expect(fn () => $this->useCase->execute($dto))
        ->toThrow(BrandDomainException::class);
});
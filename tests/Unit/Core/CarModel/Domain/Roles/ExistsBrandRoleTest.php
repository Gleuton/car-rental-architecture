<?php

declare(strict_types=1);

use App\Core\Brand\Domain\Exceptions\BrandDomainException;
use App\Core\Brand\Domain\Repositories\BrandRepositoryInterface;
use App\Core\CarModel\Domain\Roles\ExistsBrandRole;

beforeEach(function () {
    $this->repository = Mockery::mock(BrandRepositoryInterface::class);
    $this->role = new ExistsBrandRole($this->repository);
});

it('does not throw exception when brand exists', function () {
    $this->repository->shouldReceive('exists')
        ->with(1)
        ->once()
        ->andReturn(true);

    $this->role->validate(1);

    expect(true)->toBeTrue();
});

it('throws BrandDomainException with NOT_FOUND error when brand does not exist', function () {
    $brandId = 1;
    $this->repository
        ->shouldReceive('exists')
        ->with($brandId)
        ->once()
        ->andReturn(false);

    $this->role->validate($brandId);

})->throws(
    BrandDomainException::class,
    'Brand not found',
    4005
);


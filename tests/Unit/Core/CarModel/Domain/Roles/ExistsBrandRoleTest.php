<?php

declare(strict_types=1);

use App\Core\Car\Domain\Exceptions\BrandDomainException;
use App\Core\Car\Domain\Repositories\BrandRepositoryInterface;
use App\Core\Car\Domain\Roles\ExistsBrandRole;

beforeEach(function () {
    $this->repository = Mockery::mock(BrandRepositoryInterface::class);
    $this->role = new ExistsBrandRole($this->repository);
});

it('does not throw exception when brand exists', function () {
    $brandUuid = '11111111-1111-4111-8111-111111111111';

    $this->repository->shouldReceive('existsByUuid')
        ->with($brandUuid)
        ->once()
        ->andReturn(true);

    $this->role->validate($brandUuid);

    expect(true)->toBeTrue();
});

it('throws BrandDomainException with NOT_FOUND error when brand does not exist', function () {
    $brandUuid = '11111111-1111-4111-8111-111111111111';
    $this->repository
        ->shouldReceive('existsByUuid')
        ->with($brandUuid)
        ->once()
        ->andReturn(false);

    $this->role->validate($brandUuid);

})->throws(
    BrandDomainException::class,
    'Brand not found',
    4005
);

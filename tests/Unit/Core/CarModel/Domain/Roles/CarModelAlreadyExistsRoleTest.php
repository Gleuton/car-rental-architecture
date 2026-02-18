<?php

declare(strict_types=1);

use App\Core\CarModel\Domain\Exceptions\CarModelDomainException;
use App\Core\CarModel\Domain\Repositories\CarModelRepositoryInterface;
use App\Core\CarModel\Domain\Roles\CarModelAlreadyExistsRole;

beforeEach(function () {
    $this->repository = Mockery::mock(CarModelRepositoryInterface::class);
    $this->role = new CarModelAlreadyExistsRole($this->repository);
});

it('does not throw exception when car model does not exist', function () {
    $this->repository->shouldReceive('existsByNameAndBrandId')
        ->with('Civic', 1)
        ->once()
        ->andReturn(false);

    $this->role->validate('Civic', 1);

    expect(true)->toBeTrue();
});

it('throws CarModelDomainException with ALREADY_EXISTS error', function () {
    $brandId = 1;
    $carModel = 'Civic';
    $this->repository
        ->shouldReceive('existsByNameAndBrandId')
        ->with($carModel, $brandId)
        ->once()
        ->andReturn(true);

    $this->role->validate($carModel, $brandId);

})->throws(
    CarModelDomainException::class,
    'Car model already exists for this brand',
    5001
);

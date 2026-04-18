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
    $brandUuid = '11111111-1111-4111-8111-111111111111';

    $this->repository->shouldReceive('existsByNameAndBrandUuid')
        ->with('Civic', $brandUuid)
        ->once()
        ->andReturn(false);

    $this->role->validate('Civic', $brandUuid);

    expect(true)->toBeTrue();
});

it('throws CarModelDomainException with ALREADY_EXISTS error', function () {
    $brandUuid = '11111111-1111-4111-8111-111111111111';
    $carModel = 'Civic';
    $this->repository
        ->shouldReceive('existsByNameAndBrandUuid')
        ->with($carModel, $brandUuid)
        ->once()
        ->andReturn(true);

    $this->role->validate($carModel, $brandUuid);

})->throws(
    CarModelDomainException::class,
    'Car model already exists for this brand',
    5001
);

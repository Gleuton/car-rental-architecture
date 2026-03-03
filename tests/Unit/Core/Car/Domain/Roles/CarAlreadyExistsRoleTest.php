<?php

declare(strict_types=1);

use App\Core\Car\Domain\Exceptions\CarDomainException;
use App\Core\Car\Domain\Repositories\CarRepositoryInterface;
use App\Core\Car\Domain\Roles\CarAlreadyExistsRole;

it('does not throw exception when car with license plate does not exist', function () {
    $repository = Mockery::mock(CarRepositoryInterface::class);
    $repository->shouldReceive('existsByLicensePlate')
        ->with('ABC-1234')
        ->once()
        ->andReturn(false);

    $role = new CarAlreadyExistsRole($repository);
    $role->validate('ABC-1234');

    expect(true)->toBeTrue();
});

it('throws CarDomainException with ALREADY_EXISTS error when car with license plate exists', function () {
    $repository = Mockery::mock(CarRepositoryInterface::class);
    $repository->shouldReceive('existsByLicensePlate')
        ->with('ABC-1234')
        ->once()
        ->andReturn(true);

    $role = new CarAlreadyExistsRole($repository);
    $role->validate('ABC-1234');
})->throws(CarDomainException::class, 'Car with this license plate already exists');

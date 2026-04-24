<?php

declare(strict_types=1);

use App\Core\Car\Application\DTOs\UpdateCarDto;
use App\Core\Car\Application\UseCases\UpdateCarUseCase;
use App\Core\Car\Domain\Entities\Car as DomainCar;
use App\Core\Car\Domain\Errors\CarError;
use App\Core\Car\Domain\Exceptions\CarDomainException;
use App\Core\Car\Domain\Repositories\CarRepositoryInterface;
use App\Core\Car\Domain\Roles\CarAlreadyExistsRole;
use App\Http\Requests\Car\UpdateCarRequest;

beforeEach(function () {
    $this->repository = Mockery::mock(CarRepositoryInterface::class);
    $this->carAlreadyExistsRole = Mockery::mock(CarAlreadyExistsRole::class);

    $this->useCase = new UpdateCarUseCase(
        $this->repository,
        $this->carAlreadyExistsRole
    );
});

it('updates license plate, color and availability', function () {
    $carUuid = '11111111-1111-4111-8111-111111111111';
    $newLicensePlate = 'XYZ-9876';
    $newColor = 'Blue';
    $newIsAvailable = false;

    $existingCar = DomainCar::restore(
        '22222222-2222-4222-8222-222222222222',
        'ABC-1234',
        'Red',
        true,
        10000,
        $carUuid,
    );

    $request = Mockery::mock(UpdateCarRequest::class);
    $request->shouldReceive('input')->with('license_plate')->andReturn($newLicensePlate);
    $request->shouldReceive('input')->with('color')->andReturn($newColor);
    $request->shouldReceive('input')->with('is_available')->andReturn($newIsAvailable);

    $dto = UpdateCarDto::fromRequest($request, $carUuid);

    $this->repository->shouldReceive('findByUuid')
        ->with($carUuid)
        ->once()
        ->andReturn($existingCar);

    $this->carAlreadyExistsRole
        ->shouldReceive('validate')
        ->with($newLicensePlate)
        ->once();

    $this->repository->shouldReceive('update')
        ->once()
        ->with(
            Mockery::on(static function (DomainCar $car) use ($carUuid, $newLicensePlate, $newColor, $newIsAvailable): bool {
                return $car->uuid === $carUuid &&
                    $car->carModelUuid === '22222222-2222-4222-8222-222222222222' &&
                    $car->licensePlate() === $newLicensePlate &&
                    $car->color() === $newColor &&
                    $car->isAvailable() === $newIsAvailable &&
                    $car->km() === 10000;
            })
        )
        ->andReturn(DomainCar::restore(
            '22222222-2222-4222-8222-222222222222',
            $newLicensePlate,
            $newColor,
            $newIsAvailable,
            10000,
            $carUuid,
        ));

    $result = $this->useCase->execute($dto);

    expect($result->licensePlate())->toBe($newLicensePlate)
        ->and($result->color())->toBe($newColor)
        ->and($result->isAvailable())->toBe($newIsAvailable)
        ->and($result->carModelUuid)->toBe('22222222-2222-4222-8222-222222222222')
        ->and($result->km())->toBe(10000);
});

it('does not validate license plate when it remains the same', function () {
    $carUuid = '11111111-1111-4111-8111-111111111111';
    $existingLicensePlate = 'ABC-1234';

    $existingCar = DomainCar::restore(
        '22222222-2222-4222-8222-222222222222',
        $existingLicensePlate,
        'Red',
        true,
        10000,
        $carUuid,
    );

    $request = Mockery::mock(UpdateCarRequest::class);
    $request->shouldReceive('input')->with('license_plate')->andReturn($existingLicensePlate);
    $request->shouldReceive('input')->with('color')->andReturn('Blue');
    $request->shouldReceive('input')->with('is_available')->andReturn(null);

    $dto = UpdateCarDto::fromRequest($request, $carUuid);

    $this->repository->shouldReceive('findByUuid')
        ->with($carUuid)
        ->once()
        ->andReturn($existingCar);

    $this->carAlreadyExistsRole
        ->shouldNotReceive('validate');

    $this->repository->shouldReceive('update')
        ->once()
        ->andReturn(DomainCar::restore(
            '22222222-2222-4222-8222-222222222222',
            $existingLicensePlate,
            'Blue',
            true,
            10000,
            $carUuid,
        ));

    $result = $this->useCase->execute($dto);

    expect($result->color())->toBe('Blue');
});

it('throws exception when new license plate already exists', function () {
    $carUuid = '11111111-1111-4111-8111-111111111111';
    $newLicensePlate = 'XYZ-9876';

    $existingCar = DomainCar::restore(
        '22222222-2222-4222-8222-222222222222',
        'ABC-1234',
        'Red',
        true,
        10000,
        $carUuid,
    );

    $request = Mockery::mock(UpdateCarRequest::class);
    $request->shouldReceive('input')->with('license_plate')->andReturn($newLicensePlate);
    $request->shouldReceive('input')->with('color')->andReturn(null);
    $request->shouldReceive('input')->with('is_available')->andReturn(null);

    $dto = UpdateCarDto::fromRequest($request, $carUuid);

    $this->repository->shouldReceive('findByUuid')
        ->with($carUuid)
        ->once()
        ->andReturn($existingCar);

    $this->carAlreadyExistsRole
        ->shouldReceive('validate')
        ->with($newLicensePlate)
        ->once()
        ->andThrow(new CarDomainException(CarError::ALREADY_EXISTS));

    $this->repository->shouldNotReceive('update');

    $this->useCase->execute($dto);
})->throws(
    CarDomainException::class,
    'Car with this license plate already exists'
);

it('updates only color when other fields are null', function () {
    $carUuid = '11111111-1111-4111-8111-111111111111';
    $newColor = 'Green';

    $existingCar = DomainCar::restore(
        '22222222-2222-4222-8222-222222222222',
        'ABC-1234',
        'Red',
        true,
        10000,
        $carUuid,
    );

    $request = Mockery::mock(UpdateCarRequest::class);
    $request->shouldReceive('input')->with('license_plate')->andReturn(null);
    $request->shouldReceive('input')->with('color')->andReturn($newColor);
    $request->shouldReceive('input')->with('is_available')->andReturn(null);

    $dto = UpdateCarDto::fromRequest($request, $carUuid);

    $this->repository->shouldReceive('findByUuid')
        ->with($carUuid)
        ->once()
        ->andReturn($existingCar);

    $this->carAlreadyExistsRole
        ->shouldNotReceive('validate');

    $this->repository->shouldReceive('update')
        ->once()
        ->with(
            Mockery::on(static function (DomainCar $car) use ($carUuid, $newColor): bool {
                return $car->uuid === $carUuid &&
                    $car->licensePlate() === 'ABC-1234' &&
                    $car->color() === $newColor &&
                    $car->km() === 10000 &&
                    $car->carModelUuid === '22222222-2222-4222-8222-222222222222' &&
                    $car->isAvailable() === true;
            })
        )
        ->andReturn(DomainCar::restore(
            '22222222-2222-4222-8222-222222222222',
            'ABC-1234',
            $newColor,
            true,
            10000,
            $carUuid,
        ));

    $result = $this->useCase->execute($dto);

    expect($result->color())->toBe($newColor)
        ->and($result->km())->toBe(10000)
        ->and($result->licensePlate())->toBe('ABC-1234');
});

it('updates availability status successfully', function () {
    $carUuid = '11111111-1111-4111-8111-111111111111';
    $newIsAvailable = false;

    $existingCar = DomainCar::restore(
        '22222222-2222-4222-8222-222222222222',
        'ABC-1234',
        'Red',
        true,
        10000,
        $carUuid,
    );

    $request = Mockery::mock(UpdateCarRequest::class);
    $request->shouldReceive('input')->with('license_plate')->andReturn(null);
    $request->shouldReceive('input')->with('color')->andReturn(null);
    $request->shouldReceive('input')->with('is_available')->andReturn($newIsAvailable);

    $dto = UpdateCarDto::fromRequest($request, $carUuid);

    $this->repository->shouldReceive('findByUuid')
        ->with($carUuid)
        ->once()
        ->andReturn($existingCar);

    $this->carAlreadyExistsRole
        ->shouldNotReceive('validate');

    $this->repository->shouldReceive('update')
        ->once()
        ->andReturn(DomainCar::restore(
            '22222222-2222-4222-8222-222222222222',
            'ABC-1234',
            'Red',
            $newIsAvailable,
            10000,
            $carUuid,
        ));

    $result = $this->useCase->execute($dto);

    expect($result->isAvailable())->toBe($newIsAvailable);
});

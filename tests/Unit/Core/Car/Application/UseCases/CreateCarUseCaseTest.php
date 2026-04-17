<?php

declare(strict_types=1);

use App\Core\Car\Application\DTOs\CreateCarDTO;
use App\Core\Car\Application\UseCases\CreateCarUseCase;
use App\Core\Car\Domain\Entity\Car as DomainCar;
use App\Core\Car\Domain\Errors\CarError;
use App\Core\Car\Domain\Exceptions\CarDomainException;
use App\Core\Car\Domain\Repositories\CarRepositoryInterface;
use App\Core\Car\Domain\Roles\CarAlreadyExistsRole;
use App\Http\Requests\Car\StoreCarRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

function createPersistedCarModelForTests(): object
{
    $brandUuid = (string) Str::uuid();
    $carModelUuid = (string) Str::uuid();

    $brandId = DB::table('brands')->insertGetId([
        'uuid' => $brandUuid,
        'name' => 'Brand '.substr($brandUuid, 0, 8),
        'image' => 'brands/test.png',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $carModelId = DB::table('car_models')->insertGetId([
        'uuid' => $carModelUuid,
        'brand_id' => $brandId,
        'brand_uuid' => $brandUuid,
        'name' => 'Model '.substr($carModelUuid, 0, 8),
        'image' => 'car_models/test.png',
        'doors' => 4,
        'seats' => 5,
        'airbags' => true,
        'abs' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return (object) [
        'id' => $carModelId,
        'uuid' => $carModelUuid,
    ];
}

beforeEach(function () {
    $this->repository = Mockery::mock(CarRepositoryInterface::class);
    $this->carAlreadyExistsRole = Mockery::mock(CarAlreadyExistsRole::class);

    $this->useCase = new CreateCarUseCase(
        $this->repository,
        $this->carAlreadyExistsRole
    );
});

it('creates a car successfully', function () {
    $request = Mockery::mock(StoreCarRequest::class);
    $carModel = createPersistedCarModelForTests();
    $carModelId = $carModel->id;
    $licensePlate = 'ABC-1234';
    $color = 'Red';

    $km = 10000;

    $request->shouldReceive('input')->with('car_model_uuid')->andReturn($carModel->uuid);
    $request->shouldReceive('input')->with('license_plate')->andReturn($licensePlate);
    $request->shouldReceive('input')->with('color')->andReturn($color);
    $request->shouldReceive('input')->with('is_available')->andReturn(true);
    $request->shouldReceive('input')->with('km')->andReturn($km);

    $this->carAlreadyExistsRole
        ->shouldReceive('validate')
        ->with($licensePlate)
        ->once();

    $dto = CreateCarDTO::fromRequest($request);

    $this->repository->shouldReceive('save')
        ->once()
        ->with(
            Mockery::on(static function (DomainCar $car) use ($carModel, $carModelId, $licensePlate, $color, $km): bool {
                return $car->id === null &&
                    $car->carModelId === $carModelId &&
                    $car->carModelUuid === $carModel->uuid &&
                    $car->licensePlate() === $licensePlate &&
                    $car->color() === $color &&
                    $car->isAvailable() === true &&
                    $car->km() === $km;
            })
        )
        ->andReturn(DomainCar::restore(
            1,
            $carModelId,
            $carModel->uuid,
            $licensePlate,
            $color,
            true,
            $km
        ));

    $result = $this->useCase->execute($dto);

    expect($result->id)->toBe(1)
        ->and($result->carModelId)->toBe($carModelId)
        ->and($result->licensePlate())->toBe($licensePlate)
        ->and($result->color())->toBe($color)
        ->and($result->isAvailable())->toBe(true)
        ->and($result->km())->toBe($km);
});

it('throws exception when car with license plate already exists', function () {
    $request = Mockery::mock(StoreCarRequest::class);
    $carModel = createPersistedCarModelForTests();
    $licensePlate = 'ABC-1234';

    $request->shouldReceive('input')->with('car_model_uuid')->andReturn($carModel->uuid);
    $request->shouldReceive('input')->with('license_plate')->andReturn($licensePlate);
    $request->shouldReceive('input')->with('color')->andReturn('Red');
    $request->shouldReceive('input')->with('is_available')->andReturn(true);
    $request->shouldReceive('input')->with('km')->andReturn(10000);

    $this->carAlreadyExistsRole
        ->shouldReceive('validate')
        ->with($licensePlate)
        ->once()
        ->andThrow(new CarDomainException(CarError::ALREADY_EXISTS));

    $dto = CreateCarDTO::fromRequest($request);

    $this->useCase->execute($dto);
})->throws(
    CarDomainException::class,
    'Car with this license plate already exists'
);

it('validates license plate before creating car', function () {
    $request = Mockery::mock(StoreCarRequest::class);
    $carModel = createPersistedCarModelForTests();
    $licensePlate = 'ABC-1234';

    $request->shouldReceive('input')->with('car_model_uuid')->andReturn($carModel->uuid);
    $request->shouldReceive('input')->with('license_plate')->andReturn($licensePlate);
    $request->shouldReceive('input')->with('color')->andReturn('Red');
    $request->shouldReceive('input')->with('is_available')->andReturn(true);
    $request->shouldReceive('input')->with('km')->andReturn(10000);

    $this->carAlreadyExistsRole
        ->shouldReceive('validate')
        ->with($licensePlate)
        ->once()
        ->andThrow(new CarDomainException(CarError::ALREADY_EXISTS));

    $this->repository->shouldNotReceive('save');

    $dto = CreateCarDTO::fromRequest($request);

    $this->useCase->execute($dto);
})->throws(CarDomainException::class);

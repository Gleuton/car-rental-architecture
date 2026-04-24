<?php

declare(strict_types=1);

use App\Core\Car\Application\DTOs\CarModel\CreateCarModelDTO;
use App\Core\Car\Application\UseCases\CarModel\CreateCarModelUseCase;
use App\Core\Car\Domain\Entities\CarModel as DomainCarModel;
use App\Core\Car\Domain\Errors\BrandError;
use App\Core\Car\Domain\Errors\CarModelError;
use App\Core\Car\Domain\Exceptions\BrandDomainException;
use App\Core\Car\Domain\Exceptions\CarModelDomainException;
use App\Core\Car\Domain\Repositories\CarModelRepositoryInterface;
use App\Core\Car\Domain\Roles\CarModelAlreadyExistsRole;
use App\Core\Car\Domain\Roles\ExistsBrandRole;
use App\Core\Shared\Domain\Storage\DomainFile;
use App\Core\Shared\Domain\Storage\FileStorageInterface;
use App\Core\Shared\Domain\Storage\StoredFile;
use App\Http\Requests\CarModel\StoreCarModelRequest;
use Illuminate\Http\UploadedFile;

beforeEach(function () {
    $this->repository = Mockery::mock(CarModelRepositoryInterface::class);
    $this->storage = Mockery::mock(FileStorageInterface::class);
    $this->existsBrand = Mockery::mock(ExistsBrandRole::class);
    $this->carModelAlreadyRole = Mockery::mock(CarModelAlreadyExistsRole::class);

    $this->useCase = new CreateCarModelUseCase(
        $this->storage,
        $this->repository,
        $this->existsBrand,
        $this->carModelAlreadyRole
    );
});

it('creates a car model successfully', function () {
    $file = UploadedFile::fake()->create('civic.png', 120);
    $request = Mockery::mock(StoreCarModelRequest::class);
    $brandUuid = '11111111-1111-4111-8111-111111111111';
    $modelName = 'Civic';

    $request->shouldReceive('file')
        ->with('image')
        ->andReturn($file);

    $request->shouldReceive('input')->with('brand_uuid')->andReturn($brandUuid);
    $request->shouldReceive('input')->with('name')->andReturn($modelName);
    $request->shouldReceive('input')->with('doors_number')->andReturn(4);
    $request->shouldReceive('input')->with('seats_number')->andReturn(5);
    $request->shouldReceive('input')->with('airbags')->andReturn(true);
    $request->shouldReceive('input')->with('abs')->andReturn(true);

    $this->existsBrand
        ->shouldReceive('validate')
        ->with($brandUuid)
        ->once();

    $this->carModelAlreadyRole
        ->shouldReceive('validate')
        ->with($modelName, $brandUuid)
        ->once();

    $dto = CreateCarModelDTO::fromRequest($request);

    $storedFile = new StoredFile('car_models/civic_stored.png', '');

    $this->storage->shouldReceive('upload')
        ->with(
            Mockery::type(DomainFile::class),
            'car_models'
        )
        ->once()
        ->andReturn($storedFile);

    $this->repository->shouldReceive('save')
        ->once()
        ->with(
            Mockery::on(static function (DomainCarModel $carModel): bool {
                return $carModel->brandUuid === '11111111-1111-4111-8111-111111111111' &&
                    $carModel->name === 'Civic' &&
                    $carModel->image === 'car_models/civic_stored.png' &&
                    $carModel->doorsNumber === 4 &&
                    $carModel->seatsNumber === 5 &&
                    $carModel->airbags === true &&
                    $carModel->abs === true;
            })
        )
        ->andReturnUsing(static fn (DomainCarModel $carModel) => $carModel);

    $result = $this->useCase->execute($dto);

    expect($result->brandUuid)->toBe('11111111-1111-4111-8111-111111111111')
        ->and($result->name)->toBe('Civic')
        ->and($result->image)->toBe('car_models/civic_stored.png')
        ->and($result->doorsNumber)->toBe(4)
        ->and($result->seatsNumber)->toBe(5)
        ->and($result->airbags)->toBeTrue()
        ->and($result->abs)->toBeTrue();
});

it('creates a car model with invalid brand', function () {
    $file = UploadedFile::fake()->create('civic.png', 120);
    $request = Mockery::mock(StoreCarModelRequest::class);
    $brandUuid = '11111111-1111-4111-8111-111111111111';
    $request->shouldReceive('file')
        ->with('image')
        ->andReturn($file);

    $request->shouldReceive('input')->with('brand_uuid')->andReturn($brandUuid);
    $request->shouldReceive('input')->with('name')->andReturn('Civic');
    $request->shouldReceive('input')->with('doors_number')->andReturn(4);
    $request->shouldReceive('input')->with('seats_number')->andReturn(5);
    $request->shouldReceive('input')->with('airbags')->andReturn(true);
    $request->shouldReceive('input')->with('abs')->andReturn(true);

    $this->existsBrand
        ->shouldReceive('validate')
        ->with($brandUuid)
        ->once()
        ->andThrow(new BrandDomainException(BrandError::NOT_FOUND));

    $dto = CreateCarModelDTO::fromRequest($request);

    $this->useCase->execute($dto);
})->throws(BrandDomainException::class, 'Brand not found', 4005);

it('creates a car model when car model already exists', function () {
    $file = UploadedFile::fake()->create('civic.png', 120);
    $request = Mockery::mock(StoreCarModelRequest::class);
    $brandUuid = '11111111-1111-4111-8111-111111111111';
    $name = 'Civic';
    $request->shouldReceive('file')
        ->with('image')
        ->andReturn($file);

    $request->shouldReceive('input')->with('brand_uuid')->andReturn($brandUuid);
    $request->shouldReceive('input')->with('name')->andReturn($name);
    $request->shouldReceive('input')->with('doors_number')->andReturn(4);
    $request->shouldReceive('input')->with('seats_number')->andReturn(5);
    $request->shouldReceive('input')->with('airbags')->andReturn(true);
    $request->shouldReceive('input')->with('abs')->andReturn(true);

    $this->existsBrand
        ->shouldReceive('validate')
        ->with($brandUuid)
        ->once();

    $this->carModelAlreadyRole
        ->shouldReceive('validate')
        ->with($name, $brandUuid)
        ->once()
        ->andThrow(new CarModelDomainException(CarModelError::ALREADY_EXISTS));

    $dto = CreateCarModelDTO::fromRequest($request);

    $this->useCase->execute($dto);
})->throws(
    CarModelDomainException::class,
    'Car model already exists for this brand',
    5001
);

it('creates a car model with a wrong seats number', function (int $seatsNumber) {
    $file = UploadedFile::fake()->create('civic.png', 120);
    $request = Mockery::mock(StoreCarModelRequest::class);
    $brandUuid = '11111111-1111-4111-8111-111111111111';
    $name = 'Civic';

    $request->shouldReceive('file')
        ->with('image')
        ->andReturn($file);

    $request->shouldReceive('input')->with('brand_uuid')->andReturn($brandUuid);
    $request->shouldReceive('input')->with('name')->andReturn($name);
    $request->shouldReceive('input')->with('doors_number')->andReturn(4);
    $request->shouldReceive('input')->with('seats_number')->andReturn($seatsNumber);
    $request->shouldReceive('input')->with('airbags')->andReturn(true);
    $request->shouldReceive('input')->with('abs')->andReturn(true);

    $this->existsBrand
        ->shouldReceive('validate')
        ->with($brandUuid)
        ->once();

    $this->carModelAlreadyRole
        ->shouldReceive('validate')
        ->with($name, $brandUuid)
        ->once();

    $dto = CreateCarModelDTO::fromRequest($request);

    $storedFile = new StoredFile('car_models/civic_stored.png', '');

    $this->storage->shouldReceive('upload')
        ->with(
            Mockery::type(DomainFile::class),
            'car_models'
        )
        ->once()
        ->andReturn($storedFile);

    $this->storage->shouldReceive('delete')
        ->with(
            $storedFile->path
        )
        ->once();

    $this->useCase->execute($dto);
})->throws(
    CarModelDomainException::class,
    'Seats number must be between 2 and 7',
    5002
)->with([
    'more_than_7' => 8,
    'less_than_2' => 1,
]);

it('creates a car model with a wrong doors number', function (int $doorsNumber) {
    $file = UploadedFile::fake()->create('civic.png', 120);
    $request = Mockery::mock(StoreCarModelRequest::class);
    $brandUuid = '11111111-1111-4111-8111-111111111111';
    $name = 'Civic';

    $request->shouldReceive('file')
        ->with('image')
        ->andReturn($file);

    $request->shouldReceive('input')->with('brand_uuid')->andReturn($brandUuid);
    $request->shouldReceive('input')->with('name')->andReturn($name);
    $request->shouldReceive('input')->with('doors_number')->andReturn($doorsNumber);
    $request->shouldReceive('input')->with('seats_number')->andReturn(7);
    $request->shouldReceive('input')->with('airbags')->andReturn(true);
    $request->shouldReceive('input')->with('abs')->andReturn(true);

    $this->existsBrand
        ->shouldReceive('validate')
        ->with($brandUuid)
        ->once();

    $this->carModelAlreadyRole
        ->shouldReceive('validate')
        ->with($name, $brandUuid)
        ->once();

    $dto = CreateCarModelDTO::fromRequest($request);

    $storedFile = new StoredFile('car_models/civic_stored.png', '');

    $this->storage->shouldReceive('upload')
        ->with(
            Mockery::type(DomainFile::class),
            'car_models'
        )
        ->once()
        ->andReturn($storedFile);

    $this->storage->shouldReceive('delete')
        ->with(
            $storedFile->path
        )
        ->once();

    $this->useCase->execute($dto);
})->throws(
    CarModelDomainException::class,
    'Doors number must be between 2 and 5',
    5003
)->with([
    'more_than_5' => 6,
    'less_than_2' => 1,
]);

<?php

declare(strict_types=1);

use App\Core\Brand\Domain\Errors\BrandError;
use App\Core\Brand\Domain\Exceptions\BrandDomainException;
use App\Core\CarModel\Application\DTOs\CreateCarModelDTO;
use App\Core\CarModel\Application\UseCases\CreateCarModelUseCase;
use App\Core\CarModel\Domain\Entity\CarModel as DomainCarModel;
use App\Core\CarModel\Domain\Errors\CarModelError;
use App\Core\CarModel\Domain\Exceptions\CarModelDomainException;
use App\Core\CarModel\Domain\Repositories\CarModelRepositoryInterface;
use App\Core\CarModel\Domain\Roles\CarModelAlreadyExistsRole;
use App\Core\CarModel\Domain\Roles\ExistsBrandRole;
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
    $brandId = 1;
    $modelName = 'Civic';

    $request->shouldReceive('file')
        ->with('image')
        ->andReturn($file);

    $request->shouldReceive('input')->with('brand_id')->andReturn($brandId);
    $request->shouldReceive('input')->with('name')->andReturn($modelName);
    $request->shouldReceive('input')->with('doors_number')->andReturn(4);
    $request->shouldReceive('input')->with('seats_number')->andReturn(5);
    $request->shouldReceive('input')->with('airbags')->andReturn(true);
    $request->shouldReceive('input')->with('abs')->andReturn(true);

    $this->existsBrand
        ->shouldReceive('validate')
        ->with($brandId)
        ->once();

    $this->carModelAlreadyRole
        ->shouldReceive('validate')
        ->with($modelName, $brandId)
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
                return $carModel->id === null &&
                    $carModel->brandId === 1 &&
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

    expect($result->id)->toBeNull()
        ->and($result->brandId)->toBe(1)
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
    $brandId = 1;
    $request->shouldReceive('file')
        ->with('image')
        ->andReturn($file);

    $request->shouldReceive('input')->with('brand_id')->andReturn($brandId);
    $request->shouldReceive('input')->with('name')->andReturn('Civic');
    $request->shouldReceive('input')->with('doors_number')->andReturn(4);
    $request->shouldReceive('input')->with('seats_number')->andReturn(5);
    $request->shouldReceive('input')->with('airbags')->andReturn(true);
    $request->shouldReceive('input')->with('abs')->andReturn(true);

    $this->existsBrand
        ->shouldReceive('validate')
        ->with($brandId)
        ->once()
        ->andThrow(new BrandDomainException(BrandError::NOT_FOUND));

    $dto = CreateCarModelDTO::fromRequest($request);

    $this->useCase->execute($dto);
})->throws(BrandDomainException::class, 'Brand not found', 4005);

it('creates a car model when car model already exists', function () {
    $file = UploadedFile::fake()->create('civic.png', 120);
    $request = Mockery::mock(StoreCarModelRequest::class);
    $brandId = 1;
    $name = 'Civic';
    $request->shouldReceive('file')
        ->with('image')
        ->andReturn($file);

    $request->shouldReceive('input')->with('brand_id')->andReturn($brandId);
    $request->shouldReceive('input')->with('name')->andReturn($name);
    $request->shouldReceive('input')->with('doors_number')->andReturn(4);
    $request->shouldReceive('input')->with('seats_number')->andReturn(5);
    $request->shouldReceive('input')->with('airbags')->andReturn(true);
    $request->shouldReceive('input')->with('abs')->andReturn(true);

    $this->existsBrand
        ->shouldReceive('validate')
        ->with($brandId)
        ->once();

    $this->carModelAlreadyRole
        ->shouldReceive('validate')
        ->with($name, $brandId)
        ->once()
        ->andThrow(new CarModelDomainException(CarModelError::ALREADY_EXISTS));

    $dto = CreateCarModelDTO::fromRequest($request);

    $this->useCase->execute($dto);
})->throws(CarModelDomainException::class, 'Car model already exists for this brand', 5001);

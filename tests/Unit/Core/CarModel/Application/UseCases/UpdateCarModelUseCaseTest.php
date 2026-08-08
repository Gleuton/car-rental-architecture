<?php

declare(strict_types=1);

use App\Core\Car\Application\DTOs\CarModel\UpdateCarModelDTO;
use App\Core\Car\Application\UseCases\CarModel\UpdateCarModelUseCase;
use App\Core\Car\Domain\Entity\CarModel as DomainCarModel;
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
use App\Http\Requests\CarModel\UpdateCarModelRequest;
use Illuminate\Http\UploadedFile;

beforeEach(function () {
    $this->repository = Mockery::mock(CarModelRepositoryInterface::class);
    $this->storage = Mockery::mock(FileStorageInterface::class);
    $this->existsBrand = Mockery::mock(ExistsBrandRole::class);
    $this->carModelAlreadyRole = Mockery::mock(CarModelAlreadyExistsRole::class);

    $this->useCase = new UpdateCarModelUseCase(
        $this->storage,
        $this->repository,
        $this->existsBrand,
        $this->carModelAlreadyRole
    );

    $this->existingCarModel = DomainCarModel::restore(
        brandUuid: '11111111-1111-4111-8111-111111111111',
        name: 'Civic',
        image: 'car_models/civic.png',
        doorsNumber: 4,
        seatsNumber: 5,
        airbags: true,
        abs: true
    );
});

function mockUpdateRequest(
    ?string $brandUuid = null,
    ?string $name = null,
    ?UploadedFile $image = null,
    ?int $doorsNumber = null,
    ?int $seatsNumber = null,
    ?bool $airbags = null,
    ?bool $abs = null,
): UpdateCarModelRequest {
    $request = Mockery::mock(UpdateCarModelRequest::class);
    $request->shouldReceive('input')->with('brand_uuid')->andReturn($brandUuid);
    $request->shouldReceive('input')->with('name')->andReturn($name);
    $request->shouldReceive('file')->with('image')->andReturn($image);
    $request->shouldReceive('input')->with('doors_number')->andReturn($doorsNumber);
    $request->shouldReceive('input')->with('seats_number')->andReturn($seatsNumber);
    $request->shouldReceive('input')->with('airbags')->andReturn($airbags);
    $request->shouldReceive('input')->with('abs')->andReturn($abs);

    return $request;
}

it('updates a car model with all fields successfully', function () {
    $file = UploadedFile::fake()->create('corolla.png', 100);
    $request = mockUpdateRequest(
        brandUuid: '22222222-2222-4222-8222-222222222222',
        name: 'Corolla',
        image: $file,
        doorsNumber: 5,
        seatsNumber: 7,
        airbags: false,
        abs: false
    );

    $dto = UpdateCarModelDTO::fromRequest($request, 'aaaaaaaa-aaaa-4aaa-8aaa-aaaaaaaaaaaa');

    $this->existsBrand->shouldReceive('validate')->with('22222222-2222-4222-8222-222222222222')->once();

    $this->repository->shouldReceive('findByUuid')
        ->with('aaaaaaaa-aaaa-4aaa-8aaa-aaaaaaaaaaaa')
        ->once()
        ->andReturn($this->existingCarModel);

    $this->carModelAlreadyRole->shouldReceive('validate')
        ->with('Corolla', '22222222-2222-4222-8222-222222222222')
        ->once();

    $storedFile = new StoredFile('car_models/corolla.png', '');
    $this->storage->shouldReceive('upload')
        ->with(Mockery::type(DomainFile::class), 'car_models')
        ->once()
        ->andReturn($storedFile);

    $this->storage->shouldReceive('delete')
        ->with('car_models/civic.png')
        ->once();

    $this->repository->shouldReceive('update')
        ->once()
        ->with(Mockery::on(static function (DomainCarModel $carModel): bool {
            return $carModel->brandUuid === '22222222-2222-4222-8222-222222222222' &&
                $carModel->name === 'Corolla' &&
                $carModel->image === 'car_models/corolla.png' &&
                $carModel->doorsNumber === 5 &&
                $carModel->seatsNumber === 7 &&
                $carModel->airbags === false &&
                $carModel->abs === false;
        }))
        ->andReturnUsing(static fn (DomainCarModel $carModel) => $carModel);

    $result = $this->useCase->execute($dto);

    expect($result->name)->toBe('Corolla')
        ->and($result->brandUuid)->toBe('22222222-2222-4222-8222-222222222222')
        ->and($result->image)->toBe('car_models/corolla.png')
        ->and($result->doorsNumber)->toBe(5)
        ->and($result->seatsNumber)->toBe(7)
        ->and($result->airbags)->toBeFalse()
        ->and($result->abs)->toBeFalse();
});

it('updates car model name only', function () {
    $request = mockUpdateRequest(name: 'Accord');
    $dto = UpdateCarModelDTO::fromRequest($request, 'aaaaaaaa-aaaa-4aaa-8aaa-aaaaaaaaaaaa');

    $this->existsBrand->shouldNotReceive('validate');

    $this->repository->shouldReceive('findByUuid')
        ->with('aaaaaaaa-aaaa-4aaa-8aaa-aaaaaaaaaaaa')
        ->once()
        ->andReturn($this->existingCarModel);

    $this->carModelAlreadyRole->shouldReceive('validate')
        ->with('Accord', '11111111-1111-4111-8111-111111111111')
        ->once();

    $this->storage->shouldNotReceive('upload');
    $this->storage->shouldNotReceive('delete');

    $this->repository->shouldReceive('update')
        ->once()
        ->with(Mockery::on(static function (DomainCarModel $carModel): bool {
            return $carModel->name === 'Accord' &&
                $carModel->brandUuid === '11111111-1111-4111-8111-111111111111' &&
                $carModel->image === 'car_models/civic.png';
        }))
        ->andReturnUsing(static fn (DomainCarModel $carModel) => $carModel);

    $result = $this->useCase->execute($dto);

    expect($result->name)->toBe('Accord')
        ->and($result->brandUuid)->toBe('11111111-1111-4111-8111-111111111111')
        ->and($result->image)->toBe('car_models/civic.png');
});

it('updates car model brand only', function () {
    $request = mockUpdateRequest(brandUuid: '22222222-2222-4222-8222-222222222222');
    $dto = UpdateCarModelDTO::fromRequest($request, 'aaaaaaaa-aaaa-4aaa-8aaa-aaaaaaaaaaaa');

    $this->existsBrand->shouldReceive('validate')->with('22222222-2222-4222-8222-222222222222')->once();

    $this->repository->shouldReceive('findByUuid')
        ->with('aaaaaaaa-aaaa-4aaa-8aaa-aaaaaaaaaaaa')
        ->once()
        ->andReturn($this->existingCarModel);

    $this->carModelAlreadyRole->shouldNotReceive('validate');

    $this->storage->shouldNotReceive('upload');
    $this->storage->shouldNotReceive('delete');

    $this->repository->shouldReceive('update')
        ->once()
        ->with(Mockery::on(static function (DomainCarModel $carModel): bool {
            return $carModel->brandUuid === '22222222-2222-4222-8222-222222222222' &&
                $carModel->name === 'Civic';
        }))
        ->andReturnUsing(static fn (DomainCarModel $carModel) => $carModel);

    $result = $this->useCase->execute($dto);

    expect($result->brandUuid)->toBe('22222222-2222-4222-8222-222222222222')
        ->and($result->name)->toBe('Civic');
});

it('updates car model image only without validating name or brand', function () {
    $file = UploadedFile::fake()->create('civic_new.png', 100);
    $request = mockUpdateRequest(image: $file);
    $dto = UpdateCarModelDTO::fromRequest($request, 'aaaaaaaa-aaaa-4aaa-8aaa-aaaaaaaaaaaa');

    $this->existsBrand->shouldNotReceive('validate');

    $this->repository->shouldReceive('findByUuid')
        ->with('aaaaaaaa-aaaa-4aaa-8aaa-aaaaaaaaaaaa')
        ->once()
        ->andReturn($this->existingCarModel);

    $this->carModelAlreadyRole->shouldNotReceive('validate');

    $storedFile = new StoredFile('car_models/civic_new.png', '');
    $this->storage->shouldReceive('upload')
        ->with(Mockery::type(DomainFile::class), 'car_models')
        ->once()
        ->andReturn($storedFile);

    $this->storage->shouldReceive('delete')
        ->with('car_models/civic.png')
        ->once();

    $this->repository->shouldReceive('update')
        ->once()
        ->with(Mockery::on(static function (DomainCarModel $carModel): bool {
            return $carModel->image === 'car_models/civic_new.png' &&
                $carModel->name === 'Civic';
        }))
        ->andReturnUsing(static fn (DomainCarModel $carModel) => $carModel);

    $result = $this->useCase->execute($dto);

    expect($result->image)->toBe('car_models/civic_new.png')
        ->and($result->name)->toBe('Civic');
});

it('skips name uniqueness validation when name is the same', function () {
    $request = mockUpdateRequest(name: 'Civic');
    $dto = UpdateCarModelDTO::fromRequest($request, 'aaaaaaaa-aaaa-4aaa-8aaa-aaaaaaaaaaaa');

    $this->existsBrand->shouldNotReceive('validate');

    $this->repository->shouldReceive('findByUuid')
        ->with('aaaaaaaa-aaaa-4aaa-8aaa-aaaaaaaaaaaa')
        ->once()
        ->andReturn($this->existingCarModel);

    $this->carModelAlreadyRole->shouldNotReceive('validate');

    $this->storage->shouldNotReceive('upload');
    $this->storage->shouldNotReceive('delete');

    $this->repository->shouldReceive('update')
        ->once()
        ->andReturnUsing(static fn (DomainCarModel $carModel) => $carModel);

    $result = $this->useCase->execute($dto);

    expect($result->name)->toBe('Civic');
});

it('throws exception when brand does not exist during update', function () {
    $request = mockUpdateRequest(brandUuid: '99999999-9999-4999-8999-999999999999');
    $dto = UpdateCarModelDTO::fromRequest($request, 'aaaaaaaa-aaaa-4aaa-8aaa-aaaaaaaaaaaa');

    $this->existsBrand->shouldReceive('validate')
        ->with('99999999-9999-4999-8999-999999999999')
        ->once()
        ->andThrow(new BrandDomainException(BrandError::NOT_FOUND));

    expect(fn () => $this->useCase->execute($dto))
        ->toThrow(BrandDomainException::class, 'Brand not found');
});

it('throws exception when car model name already exists for the brand', function () {
    $request = mockUpdateRequest(name: 'Corolla');
    $dto = UpdateCarModelDTO::fromRequest($request, 'aaaaaaaa-aaaa-4aaa-8aaa-aaaaaaaaaaaa');

    $this->repository->shouldReceive('findByUuid')
        ->with('aaaaaaaa-aaaa-4aaa-8aaa-aaaaaaaaaaaa')
        ->once()
        ->andReturn($this->existingCarModel);

    $this->carModelAlreadyRole->shouldReceive('validate')
        ->with('Corolla', '11111111-1111-4111-8111-111111111111')
        ->once()
        ->andThrow(new CarModelDomainException(CarModelError::ALREADY_EXISTS));

    expect(fn () => $this->useCase->execute($dto))
        ->toThrow(CarModelDomainException::class, 'Car model already exists for this brand');
});

it('propagates exception when car model is not found during update', function () {
    $request = mockUpdateRequest(name: 'Corolla');
    $dto = UpdateCarModelDTO::fromRequest($request, '99999999-9999-4999-8999-999999999999');

    $this->repository->shouldReceive('findByUuid')
        ->with('99999999-9999-4999-8999-999999999999')
        ->once()
        ->andThrow(new RuntimeException('Car model not found'));

    expect(fn () => $this->useCase->execute($dto))
        ->toThrow(RuntimeException::class);
});

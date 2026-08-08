<?php

declare(strict_types=1);

use App\Core\Car\Application\DTOs\ListCarDTO;
use App\Core\Car\Application\UseCases\ListCarUseCase;
use App\Core\Car\Domain\Queries\CarQueryFilter;
use App\Core\Car\Domain\Repositories\CarRepositoryInterface;
use App\Core\Shared\Application\Pagination\PaginatedResult;
use App\Http\Requests\Car\IndexCarRequest;

it('lists cars successfully', function () {
    $request = Mockery::mock(IndexCarRequest::class);

    $request->shouldReceive('input')->with('license_plate')->andReturn('ABC-1234');
    $request->shouldReceive('input')->with('order_by')->andReturn('license_plate');
    $request->shouldReceive('input')->with('direction')->andReturn('asc');
    $request->shouldReceive('input')->with('per_page')->andReturn('15');
    $request->shouldReceive('input')->with('page')->andReturn(null);

    $dto = ListCarDTO::fromRequest($request);

    $repository = Mockery::mock(CarRepositoryInterface::class);
    $paginatedResult = Mockery::mock(PaginatedResult::class);
    $paginatedResult->items = [];
    $paginatedResult->perPage = 15;
    $paginatedResult->total = 0;
    $paginatedResult->page = 1;
    $paginatedResult->lastPage = 1;

    $repository->shouldReceive('listCars')
        ->once()
        ->with(Mockery::on(static function (CarQueryFilter $filter) {
            return $filter->licensePlate === 'ABC-1234' &&
                   $filter->orderBy === 'license_plate' &&
                   $filter->direction === 'asc' &&
                   $filter->perPage === 15 &&
                   $filter->page === 1;
        }))
        ->andReturn($paginatedResult);

    $useCase = new ListCarUseCase($repository);
    $result = $useCase->execute($dto);

    expect($result)->toBeInstanceOf(PaginatedResult::class)
        ->and($result->items)->toBe([])
        ->and($result->page)->toBe(1)
        ->and($result->perPage)->toBe(15)
        ->and($result->total)->toBe(0)
        ->and($result->lastPage)->toBe(1);
});

it('lists cars successfully with page', function () {
    $request = Mockery::mock(IndexCarRequest::class);

    $request->shouldReceive('input')->with('license_plate')->andReturn(null);
    $request->shouldReceive('input')->with('order_by')->andReturn('created_at');
    $request->shouldReceive('input')->with('direction')->andReturn('desc');
    $request->shouldReceive('input')->with('per_page')->andReturn('10');
    $request->shouldReceive('input')->with('page')->andReturn('2');

    $dto = ListCarDTO::fromRequest($request);

    $repository = Mockery::mock(CarRepositoryInterface::class);
    $paginatedResult = Mockery::mock(PaginatedResult::class);
    $paginatedResult->items = [];
    $paginatedResult->perPage = 10;
    $paginatedResult->total = 0;
    $paginatedResult->page = 2;
    $paginatedResult->lastPage = 1;

    $repository->shouldReceive('listCars')
        ->once()
        ->with(Mockery::on(static function (CarQueryFilter $filter) {
            return $filter->licensePlate === null &&
                   $filter->orderBy === 'created_at' &&
                   $filter->direction === 'desc' &&
                   $filter->perPage === 10 &&
                   $filter->page === 2;
        }))
        ->andReturn($paginatedResult);

    $useCase = new ListCarUseCase($repository);
    $result = $useCase->execute($dto);

    expect($result)->toBeInstanceOf(PaginatedResult::class)
        ->and($result->items)->toBe([])
        ->and($result->page)->toBe(2)
        ->and($result->perPage)->toBe(10)
        ->and($result->total)->toBe(0)
        ->and($result->lastPage)->toBe(1);
});

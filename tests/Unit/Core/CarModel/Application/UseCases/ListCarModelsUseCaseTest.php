<?php

declare(strict_types=1);

use App\Core\CarModel\Application\DTOs\FilterCarModelDTO;
use App\Core\CarModel\Application\UseCases\ListCarModelsUseCase;
use App\Core\CarModel\Domain\Entity\CarModelFilter;
use App\Core\CarModel\Domain\Repositories\CarModelRepositoryInterface;
use App\Core\Shared\Application\Pagination\PaginatedResult;
use App\Http\Requests\CarModel\IndexCarModelRequest;

it('lists car models successfully', function () {

    $request = Mockery::mock(IndexCarModelRequest::class);

    $request->shouldReceive('input')->with('search')->andReturn('Corolla');
    $request->shouldReceive('input')->with('order_by')->andReturn('name');
    $request->shouldReceive('input')->with('direction')->andReturn('asc');
    $request->shouldReceive('input')->with('per_page')->andReturn('15');
    $request->shouldReceive('input')->with('page')->andReturn(null);

    $dto = FilterCarModelDTO::fromRequest($request);

    $repository = Mockery::mock(CarModelRepositoryInterface::class);
    $paginatedResult = Mockery::mock(PaginatedResult::class);
    $paginatedResult->items = [];
    $paginatedResult->perPage = 15;
    $paginatedResult->total = 0;
    $paginatedResult->page = 1;
    $paginatedResult->lastPage = 1;

    $repository->shouldReceive('findByFilters')
        ->once()
        ->with(Mockery::on(static function (CarModelFilter $filter) {
            return $filter->search === 'Corolla' &&
                   $filter->orderBy === 'name' &&
                   $filter->direction === 'asc' &&
                   $filter->perPage === 15 &&
                   $filter->page === 1;
        }))
        ->andReturn($paginatedResult);

    $useCase = new ListCarModelsUseCase($repository);
    $result = $useCase->execute($dto);

    expect($result)->toBeInstanceOf(PaginatedResult::class)
        ->and($result->items)->toBe([])
        ->and($result->page)->toBe(1)
        ->and($result->perPage)->toBe(15)
        ->and($result->total)->toBe(0)
        ->and($result->lastPage)->toBe(1);
});

it('lists car models successfully with page', function () {

    $request = Mockery::mock(IndexCarModelRequest::class);

    $request->shouldReceive('input')->with('search')->andReturn('Civic');
    $request->shouldReceive('input')->with('order_by')->andReturn('name');
    $request->shouldReceive('input')->with('direction')->andReturn('desc');
    $request->shouldReceive('input')->with('per_page')->andReturn('10');
    $request->shouldReceive('input')->with('page')->andReturn('2');

    $dto = FilterCarModelDTO::fromRequest($request);

    $repository = Mockery::mock(CarModelRepositoryInterface::class);
    $paginatedResult = Mockery::mock(PaginatedResult::class);
    $paginatedResult->items = [];
    $paginatedResult->perPage = 10;
    $paginatedResult->total = 0;
    $paginatedResult->page = 2;
    $paginatedResult->lastPage = 1;

    $repository->shouldReceive('findByFilters')
        ->once()
        ->with(Mockery::on(static function (CarModelFilter $filter) {
            return $filter->search === 'Civic' &&
                $filter->orderBy === 'name' &&
                $filter->direction === 'desc' &&
                $filter->perPage === 10 &&
                $filter->page === 2;
        }))
        ->andReturn($paginatedResult);

    $useCase = new ListCarModelsUseCase($repository);
    $result = $useCase->execute($dto);

    expect($result)->toBeInstanceOf(PaginatedResult::class)
        ->and($result->items)->toBe([])
        ->and($result->page)->toBe(2)
        ->and($result->perPage)->toBe(10)
        ->and($result->total)->toBe(0)
        ->and($result->lastPage)->toBe(1);
});

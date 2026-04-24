<?php

declare(strict_types=1);

use App\Core\Brand\Domain\Query\BrandQueryFilter;
use App\Core\Brand\Domain\Repositories\BrandRepositoryInterface;
use App\Core\Car\Application\DTOs\Brand\FilterBrandDTO;
use App\Core\Car\Application\UseCases\Brand\ListBrandsUseCase;
use App\Core\Shared\Application\Pagination\PaginatedResult;
use App\Http\Requests\Brand\IndexBrandRequest;

it('lists brands successfully', function () {

    $request = Mockery::mock(IndexBrandRequest::class);

    $request->shouldReceive('input')->with('search')->andReturn('Fiat');
    $request->shouldReceive('input')->with('order_by')->andReturn('name');
    $request->shouldReceive('input')->with('direction')->andReturn('asc');
    $request->shouldReceive('input')->with('per_page')->andReturn('15');
    $request->shouldReceive('input')->with('page')->andReturn(null);

    $dto = FilterBrandDTO::fromRequest($request);

    $repository = Mockery::mock(BrandRepositoryInterface::class);
    $paginatedResult = Mockery::mock(PaginatedResult::class);
    $paginatedResult->items = [];
    $paginatedResult->perPage = 15;
    $paginatedResult->total = 0;
    $paginatedResult->page = 1;
    $paginatedResult->lastPage = 1;

    $repository->shouldReceive('findByFilters')
        ->once()
        ->with(Mockery::on(static function (BrandQueryFilter $filter) {
            return $filter->search === 'Fiat' &&
                   $filter->orderBy === 'name' &&
                   $filter->direction === 'asc' &&
                   $filter->perPage === 15 &&
                   $filter->page === 1;
        }))
        ->andReturn($paginatedResult);

    $useCase = new ListBrandsUseCase($repository);
    $result = $useCase->execute($dto);

    expect($result)->toBeInstanceOf(PaginatedResult::class)
        ->and($result->items)->toBe([])
        ->and($result->page)->toBe(1)
        ->and($result->perPage)->toBe(15)
        ->and($result->total)->toBe(0)
        ->and($result->lastPage)->toBe(1);
});

it('lists brands successfully with page', function () {

    $request = Mockery::mock(IndexBrandRequest::class);

    $request->shouldReceive('input')->with('search')->andReturn('Fiat');
    $request->shouldReceive('input')->with('order_by')->andReturn('name');
    $request->shouldReceive('input')->with('direction')->andReturn('asc');
    $request->shouldReceive('input')->with('per_page')->andReturn('15');
    $request->shouldReceive('input')->with('page')->andReturn('2');

    $dto = FilterBrandDTO::fromRequest($request);

    $repository = Mockery::mock(BrandRepositoryInterface::class);
    $paginatedResult = Mockery::mock(PaginatedResult::class);
    $paginatedResult->items = [];
    $paginatedResult->perPage = 15;
    $paginatedResult->total = 0;
    $paginatedResult->page = 2;
    $paginatedResult->lastPage = 1;

    $repository->shouldReceive('findByFilters')
        ->once()
        ->with(Mockery::on(static function (BrandQueryFilter $filter) {
            return $filter->search === 'Fiat' &&
                $filter->orderBy === 'name' &&
                $filter->direction === 'asc' &&
                $filter->perPage === 15 &&
                $filter->page === 2;
        }))
        ->andReturn($paginatedResult);

    $useCase = new ListBrandsUseCase($repository);
    $result = $useCase->execute($dto);

    expect($result)->toBeInstanceOf(PaginatedResult::class)
        ->and($result->items)->toBe([])
        ->and($result->page)->toBe(2)
        ->and($result->perPage)->toBe(15)
        ->and($result->total)->toBe(0)
        ->and($result->lastPage)->toBe(1);
});

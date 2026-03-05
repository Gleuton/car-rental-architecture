<?php

declare(strict_types=1);

use App\Core\Client\Application\DTOs\FilterClientDTO;
use App\Core\Client\Application\UseCases\ListClientsUseCase;
use App\Core\Client\Domain\Entity\ClientFilter;
use App\Core\Client\Domain\Repositories\ClientRepositoryInterface;
use App\Core\Shared\Application\Pagination\PaginatedResult;
use App\Http\Requests\Client\IndexClientRequest;

it('lists clients successfully', function () {

    $request = Mockery::mock(IndexClientRequest::class);

    $request->shouldReceive('input')->with('search')->andReturn('John');
    $request->shouldReceive('input')->with('order_by')->andReturn('name');
    $request->shouldReceive('input')->with('direction')->andReturn('asc');
    $request->shouldReceive('input')->with('per_page')->andReturn('15');
    $request->shouldReceive('input')->with('page')->andReturn(null);

    $dto = FilterClientDTO::fromRequest($request);

    $repository = Mockery::mock(ClientRepositoryInterface::class);
    $paginatedResult = Mockery::mock(PaginatedResult::class);
    $paginatedResult->items = [];
    $paginatedResult->perPage = 15;
    $paginatedResult->total = 0;
    $paginatedResult->page = 1;
    $paginatedResult->lastPage = 1;

    $repository->shouldReceive('findByFilters')
        ->once()
        ->with(Mockery::on(static function (ClientFilter $filter) {
            return $filter->search === 'John' &&
                   $filter->orderBy === 'name' &&
                   $filter->direction === 'asc' &&
                   $filter->perPage === 15 &&
                   $filter->page === 1;
        }))
        ->andReturn($paginatedResult);

    $useCase = new ListClientsUseCase($repository);
    $result = $useCase->execute($dto);

    expect($result)->toBeInstanceOf(PaginatedResult::class)
        ->and($result->items)->toBe([])
        ->and($result->page)->toBe(1)
        ->and($result->perPage)->toBe(15)
        ->and($result->total)->toBe(0)
        ->and($result->lastPage)->toBe(1);
});

it('lists clients successfully with page', function () {

    $request = Mockery::mock(IndexClientRequest::class);

    $request->shouldReceive('input')->with('search')->andReturn('John');
    $request->shouldReceive('input')->with('order_by')->andReturn('name');
    $request->shouldReceive('input')->with('direction')->andReturn('asc');
    $request->shouldReceive('input')->with('per_page')->andReturn('15');
    $request->shouldReceive('input')->with('page')->andReturn('2');

    $dto = FilterClientDTO::fromRequest($request);

    $repository = Mockery::mock(ClientRepositoryInterface::class);
    $paginatedResult = Mockery::mock(PaginatedResult::class);
    $paginatedResult->items = [];
    $paginatedResult->perPage = 15;
    $paginatedResult->total = 0;
    $paginatedResult->page = 2;
    $paginatedResult->lastPage = 1;

    $repository->shouldReceive('findByFilters')
        ->once()
        ->with(Mockery::on(static function (ClientFilter $filter) {
            return $filter->search === 'John' &&
                $filter->orderBy === 'name' &&
                $filter->direction === 'asc' &&
                $filter->perPage === 15 &&
                $filter->page === 2;
        }))
        ->andReturn($paginatedResult);

    $useCase = new ListClientsUseCase($repository);
    $result = $useCase->execute($dto);

    expect($result)->toBeInstanceOf(PaginatedResult::class)
        ->and($result->items)->toBe([])
        ->and($result->page)->toBe(2)
        ->and($result->perPage)->toBe(15)
        ->and($result->total)->toBe(0)
        ->and($result->lastPage)->toBe(1);
});

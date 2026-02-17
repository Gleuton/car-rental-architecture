<?php

use App\Core\Brand\Application\UseCases\ListBrandsUseCase;
use App\Core\Brand\Application\DTOs\FilterBrandDTO;
use App\Core\Brand\Domain\Repositories\BrandRepositoryInterface;
use App\Core\Brand\Domain\Entity\BrandFilter;
use App\Core\Shared\Application\Pagination\PaginatedResult;
use App\Http\Requests\Brand\IndexBrandRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

it('deve listar marcas com sucesso', function () {

    $request = Mockery::mock(IndexBrandRequest::class);

    $request->shouldReceive('input')->with('search')->andReturn('Fiat');
    $request->shouldReceive('input')->with('order_by')->andReturn('name');
    $request->shouldReceive('input')->with('direction')->andReturn('asc');
    $request->shouldReceive('input')->with('per_page')->andReturn('15');
    $request->shouldReceive('input')->with('page')->andReturn(null);

    $dto = FilterBrandDTO::fromRequest($request);
    
    $repository = Mockery::mock(BrandRepositoryInterface::class);
    $paginator = Mockery::mock(LengthAwarePaginator::class);

    $paginator->shouldReceive('items')->andReturn([]);
    $paginator->shouldReceive('total')->andReturn(0);
    $paginator->shouldReceive('currentPage')->andReturn(1);
    $paginator->shouldReceive('lastPage')->andReturn(1);
    $paginator->shouldReceive('perPage')->andReturn(15);

    $repository->shouldReceive('findByFilters')
        ->once()
        ->with(Mockery::on(static function (BrandFilter $filter) {
            return $filter->search === 'Fiat' && 
                   $filter->orderBy === 'name' && 
                   $filter->direction === 'asc' && 
                   $filter->perPage === 15;
        }))
        ->andReturn($paginator);

    $useCase = new ListBrandsUseCase($repository);
    $result = $useCase->execute($dto);

    expect($result)->toBeInstanceOf(PaginatedResult::class)
        ->and($result->items)->toBe([])
        ->and($result->page)->toBe(1)
        ->and($result->perPage)->toBe(15)
        ->and($result->total)->toBe(0)
        ->and($result->lastPage)->toBe(1);
});

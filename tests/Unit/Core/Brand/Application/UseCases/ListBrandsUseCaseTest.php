<?php

use App\Core\Brand\Application\UseCases\ListBrandsUseCase;
use App\Core\Brand\Application\DTOs\FilterBrandDTO;
use App\Core\Brand\Domain\Repositories\BrandRepositoryInterface;
use App\Core\Brand\Domain\Entity\BrandFilter;
use App\Http\Requests\Brand\IndexBrandRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

it('deve listar marcas com sucesso', function () {

    $request = Mockery::mock(IndexBrandRequest::class);
    $request->search = 'Fiat';
    $request->order_by = 'name';
    $request->direction = 'asc';
    $request->per_page = '15';
    $request->page = null;

    $dto = FilterBrandDTO::fromRequest($request);
    
    $repository = Mockery::mock(BrandRepositoryInterface::class);
    $paginator = Mockery::mock(LengthAwarePaginator::class);

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

    expect($result)->toBe($paginator);
});

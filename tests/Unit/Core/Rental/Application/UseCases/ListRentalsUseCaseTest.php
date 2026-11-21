<?php

declare(strict_types=1);

use App\Core\Rental\Application\DTOs\FilterRentalDTO;
use App\Core\Rental\Application\UseCases\ListRentalsUseCase;
use App\Core\Rental\Domain\Entity\RentalCollection;
use App\Core\Rental\Domain\Entity\RentalFilter;
use App\Core\Rental\Domain\Repositories\RentalRepositoryInterface;
use App\Core\Shared\Application\Pagination\PaginatedResult;
use App\Http\Requests\Rental\IndexRentalRequest;

beforeEach(function () {
    $this->repository = Mockery::mock(RentalRepositoryInterface::class);
    $this->useCase = new ListRentalsUseCase($this->repository);

    $this->makeDto = static function (array $inputs): FilterRentalDTO {
        $request = Mockery::mock(IndexRentalRequest::class);

        foreach ($inputs as $key => $value) {
            $request->shouldReceive('input')->with($key)->andReturn($value);
        }

        return FilterRentalDTO::fromRequest($request);
    };
});

it('maps every filter from the DTO to the domain filter', function () {
    $dto = ($this->makeDto)([
        'start_date_from' => '2026-01-01',
        'start_date_to' => '2026-01-31',
        'end_date_from' => '2026-02-01',
        'end_date_to' => '2026-02-28',
        'order_by' => 'start_date',
        'direction' => 'desc',
        'per_page' => '10',
        'page' => '3',
    ]);

    $expectedResult = new PaginatedResult(new RentalCollection(), 3, 10, 0, 1);

    $this->repository->shouldReceive('findByFilters')
        ->once()
        ->with(Mockery::on(static fn (RentalFilter $filter): bool => $filter->startDateFrom === '2026-01-01'
            && $filter->startDateTo === '2026-01-31'
            && $filter->endDateFrom === '2026-02-01'
            && $filter->endDateTo === '2026-02-28'
            && $filter->orderBy === 'start_date'
            && $filter->direction === 'desc'
            && $filter->perPage === 10
            && $filter->page === 3))
        ->andReturn($expectedResult);

    $result = $this->useCase->execute($dto);

    expect($result)->toBe($expectedResult);
});

it('lists rentals without date filters and defaults to the first page', function () {
    $dto = ($this->makeDto)([
        'start_date_from' => null,
        'start_date_to' => null,
        'end_date_from' => null,
        'end_date_to' => null,
        'order_by' => 'created_at',
        'direction' => 'asc',
        'per_page' => '15',
        'page' => null,
    ]);

    $expectedResult = new PaginatedResult(new RentalCollection(), 1, 15, 0, 1);

    $this->repository->shouldReceive('findByFilters')
        ->once()
        ->with(Mockery::on(static fn (RentalFilter $filter): bool => $filter->startDateFrom === null
            && $filter->startDateTo === null
            && $filter->endDateFrom === null
            && $filter->endDateTo === null
            && $filter->orderBy === 'created_at'
            && $filter->direction === 'asc'
            && $filter->perPage === 15
            && $filter->page === 1))
        ->andReturn($expectedResult);

    $result = $this->useCase->execute($dto);

    expect($result)->toBe($expectedResult)
        ->and($result->items)->toBeInstanceOf(RentalCollection::class)
        ->and($result->items->isEmpty())->toBeTrue();
});

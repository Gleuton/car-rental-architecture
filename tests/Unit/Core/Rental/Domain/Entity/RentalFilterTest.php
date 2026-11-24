<?php

declare(strict_types=1);

use App\Core\Rental\Domain\Entity\RentalFilter;

it('creates a filter keeping every criterion in its own field', function () {
    $filter = RentalFilter::create(
        startDateFrom: '2026-01-01',
        startDateTo: '2026-01-31',
        endDateFrom: '2026-02-01',
        endDateTo: '2026-02-28',
        orderBy: 'start_date',
        direction: 'desc',
        perPage: 10,
        page: 3,
    );

    expect($filter->startDateFrom)->toBe('2026-01-01')
        ->and($filter->startDateTo)->toBe('2026-01-31')
        ->and($filter->endDateFrom)->toBe('2026-02-01')
        ->and($filter->endDateTo)->toBe('2026-02-28')
        ->and($filter->orderBy)->toBe('start_date')
        ->and($filter->direction)->toBe('desc')
        ->and($filter->perPage)->toBe(10)
        ->and($filter->page)->toBe(3);
});

it('creates a filter without date criteria', function () {
    $filter = RentalFilter::create(
        startDateFrom: null,
        startDateTo: null,
        endDateFrom: null,
        endDateTo: null,
        orderBy: 'created_at',
        direction: 'asc',
        perPage: 15,
        page: 1,
    );

    expect($filter->startDateFrom)->toBeNull()
        ->and($filter->startDateTo)->toBeNull()
        ->and($filter->endDateFrom)->toBeNull()
        ->and($filter->endDateTo)->toBeNull()
        ->and($filter->orderBy)->toBe('created_at')
        ->and($filter->direction)->toBe('asc')
        ->and($filter->perPage)->toBe(15)
        ->and($filter->page)->toBe(1);
});

<?php

declare(strict_types=1);

use App\Core\Brand\Domain\Query\BrandQueryFilter;

it('can create a BrandQueryFilter', function () {
    $filter = BrandQueryFilter::create('Fiat', 'name', 'asc', 15, 1);

    expect($filter->search)->toBe('Fiat')
        ->and($filter->orderBy)->toBe('name')
        ->and($filter->direction)->toBe('asc')
        ->and($filter->perPage)->toBe(15)
        ->and($filter->page)->toBe(1);
});

it('can create a BrandQueryFilter with null search', function () {
    $filter = BrandQueryFilter::create(null, 'created_at', 'desc', 10, 2);

    expect($filter->search)->toBeNull()
        ->and($filter->orderBy)->toBe('created_at')
        ->and($filter->direction)->toBe('desc')
        ->and($filter->perPage)->toBe(10)
        ->and($filter->page)->toBe(2);
});

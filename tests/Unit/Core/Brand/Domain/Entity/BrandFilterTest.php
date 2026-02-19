<?php

declare(strict_types=1);

use App\Core\Brand\Domain\Entity\BrandFilter;

it('can create a BrandFilter', function () {
    $filter = BrandFilter::create('Fiat', 'name', 'asc', 15, 1);

    expect($filter->search)->toBe('Fiat')
        ->and($filter->orderBy)->toBe('name')
        ->and($filter->direction)->toBe('asc')
        ->and($filter->perPage)->toBe(15)
        ->and($filter->page)->toBe(1);
});

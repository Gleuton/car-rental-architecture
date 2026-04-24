<?php

declare(strict_types=1);

namespace App\Core\Brand\Infra\Persistence;

use App\Core\Brand\Domain\Exceptions\BrandDomainException;
use App\Core\Brand\Domain\Query\BrandQueryFilter;
use App\Core\Brand\Domain\Repositories\BrandRepositoryInterface;
use App\Core\Car\Domain\Collection\BrandCollection;
use App\Core\Car\Domain\Entities\Brand as DomainBrand;
use App\Core\Shared\Application\Pagination\PaginatedResult;
use App\Core\Shared\Infra\Adapters\LaravelPaginatorAdapter;
use App\Models\Brand as EloquentBrand;

class EloquentBrandRepository implements BrandRepositoryInterface
{
    public function existsByName(string $name): bool
    {
        return EloquentBrand::whereRaw(
            'LOWER(name) = ?',
            [mb_strtolower($name)]
        )->exists();
    }

    /**
     * @return PaginatedResult<BrandCollection>
     */
    public function findByFilters(BrandQueryFilter $filters): PaginatedResult
    {
        $paginator = EloquentBrand::query()
            ->when(
                $filters->search,
                fn ($q) => $q->whereRaw('LOWER(name) LIKE ?', ['%'.mb_strtolower($filters->search).'%'])
            )
            ->orderBy($filters->orderBy, $filters->direction)
            ->paginate($filters->perPage, ['*'], 'page', $filters->page);

        return LaravelPaginatorAdapter::adapt(
            $paginator,
            fn (EloquentBrand $model) => $this->toDomainBrand($model),
            static fn (array $items): BrandCollection => new BrandCollection($items)
        );
    }

    /**
     * @throws BrandDomainException
     */
    public function save(DomainBrand $brand): DomainBrand
    {
        $model = EloquentBrand::create([
            'uuid' => $brand->uuid(),
            'name' => $brand->name(),
            'image' => $brand->imagePath(),
        ]);

        return $this->toDomainBrand($model);
    }

    public function findByUuid(string $uuid): DomainBrand
    {
        $model = EloquentBrand::query()->where('uuid', $uuid)->firstOrFail();

        return $this->toDomainBrand($model);
    }

    /**
     * @throws BrandDomainException
     */
    public function update(DomainBrand $brand): DomainBrand
    {
        $model = EloquentBrand::query()->where('uuid', $brand->uuid())->firstOrFail();

        $model->update([
            'name' => $brand->name(),
            'image' => $brand->imagePath(),
        ]);

        return $this->toDomainBrand($model);
    }

    public function deleteByUuid(string $uuid): void
    {
        $model = EloquentBrand::query()->where('uuid', $uuid)->firstOrFail();
        $model->delete();
    }

    public function existsByUuid(string $brandUuid): bool
    {
        return EloquentBrand::query()->where('uuid', $brandUuid)->exists();
    }

    /**
     * @throws BrandDomainException
     */
    private function toDomainBrand(EloquentBrand $model): DomainBrand
    {
        return DomainBrand::create(
            $model->name,
            $model->image,
            $model->uuid,
        );
    }
}

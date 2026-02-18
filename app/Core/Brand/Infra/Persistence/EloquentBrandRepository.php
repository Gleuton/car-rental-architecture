<?php

declare(strict_types=1);

namespace App\Core\Brand\Infra\Persistence;

use App\Core\Brand\Domain\Entity\Brand as DomainBrand;
use App\Core\Brand\Domain\Entity\BrandFilter;
use App\Core\Brand\Domain\Exceptions\BrandDomainException;
use App\Core\Brand\Domain\Repositories\BrandRepositoryInterface;
use App\Core\Brand\Infra\Mappers\EloquentBrandMapper;
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
     * @return PaginatedResult<DomainBrand>
     */
    public function findByFilters(BrandFilter $filters): PaginatedResult
    {
        $paginator = EloquentBrand::query()
            ->when(
                $filters->search,
                fn ($q) => $q->whereRaw('LOWER(name) LIKE ?', ['%'.strtolower($filters->search).'%'])
            )
            ->orderBy($filters->orderBy, $filters->direction)
            ->paginate($filters->perPage);

        return LaravelPaginatorAdapter::adapt(
            $paginator,
            static fn (EloquentBrand $model) => EloquentBrandMapper::toDomain($model)
        );
    }

    /**
     * @throws BrandDomainException
     */
    public function save(DomainBrand $brand): DomainBrand
    {
        $model = EloquentBrand::create([
            'name' => $brand->name,
            'image' => $brand->image,
        ]);

        return EloquentBrandMapper::toDomain($model);
    }

    /**
     * @throws BrandDomainException
     */
    public function findById(int $id): DomainBrand
    {
        $model = EloquentBrand::findOrFail($id);

        return EloquentBrandMapper::toDomain($model);
    }

    /**
     * @throws BrandDomainException
     */
    public function update(DomainBrand $brand): DomainBrand
    {
        $model = EloquentBrand::findOrFail($brand->id);

        $model->update([
            'name' => $brand->name,
            'image' => $brand->image,
        ]);

        return EloquentBrandMapper::toDomain($model);
    }

    public function delete(int $id): void
    {
        $model = EloquentBrand::findOrFail($id);
        $model->delete();
    }

    public function exists(int $brandId): bool
    {
        return EloquentBrand::findOrFail($brandId)
            ->first()
            ->exists();
    }
}

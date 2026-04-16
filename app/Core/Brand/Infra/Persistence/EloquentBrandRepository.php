<?php

declare(strict_types=1);

namespace App\Core\Brand\Infra\Persistence;

use App\Core\Brand\Domain\Entity\Brand as DomainBrand;
use App\Core\Brand\Domain\Entity\BrandCollection;
use App\Core\Brand\Domain\Entity\BrandFilter;
use App\Core\Brand\Domain\Exceptions\BrandDomainException;
use App\Core\Brand\Domain\Repositories\BrandRepositoryInterface;
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
    public function findByFilters(BrandFilter $filters): PaginatedResult
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

    /**
     * @throws BrandDomainException
     */
    public function findById(int $id): DomainBrand
    {
        $model = EloquentBrand::findOrFail($id);

        return $this->toDomainBrand($model);
    }

    /**
     * @throws BrandDomainException
     */
    public function update(DomainBrand $brand): DomainBrand
    {
        $model = EloquentBrand::findOrFail($brand->id());

        $model->update([
            'name' => $brand->name(),
            'image' => $brand->imagePath(),
        ]);

        return $this->toDomainBrand($model);
    }

    public function delete(int $id): void
    {
        $model = EloquentBrand::findOrFail($id);
        $model->delete();
    }

    public function exists(int $brandId): bool
    {
        return EloquentBrand::whereKey($brandId)->exists();
    }

    /**
     * @throws BrandDomainException
     */
    private function toDomainBrand(EloquentBrand $model): DomainBrand
    {
        return DomainBrand::restore(
            $model->id,
            $model->name,
            $model->image,
            $model->uuid,
        );
    }
}

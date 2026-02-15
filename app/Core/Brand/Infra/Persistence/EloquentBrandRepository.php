<?php

namespace App\Core\Brand\Infra\Persistence;

use App\Core\Brand\Domain\Entity\Brand as DomainBrand;
use App\Core\Brand\Domain\Entity\BrandCollection;
use App\Core\Brand\Domain\Entity\BrandFilter;
use App\Core\Brand\Domain\Exceptions\BrandDomainException;
use App\Core\Brand\Domain\Repositories\BrandRepositoryInterface;
use App\Models\Brand as EloquentBrand;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentBrandRepository implements BrandRepositoryInterface
{
    public function existsByName(string $name): bool
    {
        return EloquentBrand::whereRaw(
            'LOWER(name) = ?',
            [mb_strtolower($name)]
        )->exists();
    }

    public function findByFilters(BrandFilter $filters): LengthAwarePaginator
    {
        $paginator = EloquentBrand::query()
            ->when(
                $filters->search,
                fn ($q) => $q->whereRaw('LOWER(name) LIKE ?', ['%'.strtolower($filters->search).'%'])
            )
            ->orderBy($filters->orderBy, $filters->direction)
            ->paginate($filters->perPage);

        $items = $paginator->getCollection()->map(fn (EloquentBrand $model) => DomainBrand::restore(
            $model->id,
            $model->name,
            $model->image
        ));

        return $paginator->setCollection(new BrandCollection($items->all()));
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

        return DomainBrand::restore(
            $model->id,
            $model->name,
            $model->image
        );
    }

    /**
     * @throws BrandDomainException
     */
    public function findById(int $id): DomainBrand
    {
        $model = EloquentBrand::findOrFail($id);

        return DomainBrand::restore(
            $model->id,
            $model->name,
            $model->image
        );
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

        return DomainBrand::restore(
            $model->id,
            $model->name,
            $model->image
        );
    }

    public function delete(int $id): void
    {
        $model = EloquentBrand::findOrFail($id);
        $model->delete();
    }
}

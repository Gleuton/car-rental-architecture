<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Brand\Application\DTOs\BrandIdDTO;
use App\Core\Brand\Application\DTOs\CreateBrandDTO;
use App\Core\Brand\Application\DTOs\FilterBrandDTO;
use App\Core\Brand\Application\DTOs\UpdateBrandDTO;
use App\Core\Brand\Application\UseCases\CreateBrandUseCase;
use App\Core\Brand\Application\UseCases\DeleteBrandUseCase;
use App\Core\Brand\Application\UseCases\FindBrandByIdUseCase;
use App\Core\Brand\Application\UseCases\ListBrandsUseCase;
use App\Core\Brand\Application\UseCases\UpdateBrandUseCase;
use App\Core\Brand\Domain\Exceptions\BrandDomainException;
use App\Http\Requests\Brand\IndexBrandRequest;
use App\Http\Requests\Brand\StoreBrandRequest;
use App\Http\Requests\Brand\UpdateBrandRequest;
use App\Http\Resources\BrandResource;
use Illuminate\Http\JsonResponse;

class BrandController extends Controller
{
    public function __construct(
        private readonly ListBrandsUseCase $listBrandsUseCase,
        private readonly CreateBrandUseCase $createBrandUseCase,
        private readonly FindBrandByIdUseCase $findBrandByIdUseCase,
        private readonly UpdateBrandUseCase $updateBrandUseCase,
        private readonly DeleteBrandUseCase $deleteBrandUseCase
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(IndexBrandRequest $request): JsonResponse
    {
        $filters = FilterBrandDTO::fromRequest($request);

        $brands = $this->listBrandsUseCase->execute($filters);

        return response()->json(BrandResource::PaginatedToArray($brands));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @throws BrandDomainException
     */
    public function store(StoreBrandRequest $request): JsonResponse
    {
        $dto = CreateBrandDTO::fromRequest($request);

        $brand = $this->createBrandUseCase->execute($dto);

        return response()->json(['data' => BrandResource::BrandToArray($brand)], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $brandId): JsonResponse
    {
        $brandDto = BrandIdDTO::fromId($brandId);

        $brand = $this->findBrandByIdUseCase->execute($brandDto);

        return response()->json([
            'data' => BrandResource::BrandToArray($brand),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @throws BrandDomainException
     */
    public function update(UpdateBrandRequest $request, int $brandId): JsonResponse
    {
        $brandDto = UpdateBrandDTO::fromRequestId($request, $brandId);

        $brand = $this->updateBrandUseCase->execute($brandDto);

        return response()->json([
            'data' => BrandResource::BrandToArray($brand),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $brandId): JsonResponse
    {
        $brandDto = BrandIdDTO::fromId($brandId);

        $this->deleteBrandUseCase->execute($brandDto);

        return response()->json([], 204);
    }
}

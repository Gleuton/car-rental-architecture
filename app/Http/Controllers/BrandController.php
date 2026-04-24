<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Brand\Domain\Exceptions\BrandDomainException;
use App\Core\Car\Application\DTOs\Brand\CreateBrandDTO;
use App\Core\Car\Application\DTOs\Brand\FilterBrandDTO;
use App\Core\Car\Application\DTOs\Brand\UpdateBrandDTO;
use App\Core\Car\Application\UseCases\Brand\CreateBrandUseCase;
use App\Core\Car\Application\UseCases\Brand\DeleteBrandByUuidUseCase;
use App\Core\Car\Application\UseCases\Brand\FindBrandByUuidUseCase;
use App\Core\Car\Application\UseCases\Brand\ListBrandsUseCase;
use App\Core\Car\Application\UseCases\Brand\UpdateBrandUseCase;
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
        private readonly FindBrandByUuidUseCase $findBrandByUuidUseCase,
        private readonly UpdateBrandUseCase $updateBrandUseCase,
        private readonly DeleteBrandByUuidUseCase $deleteBrandByUuidUseCase
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
    public function show(string $brandUuid): JsonResponse
    {
        $brand = $this->findBrandByUuidUseCase->execute($brandUuid);

        return response()->json([
            'data' => BrandResource::BrandToArray($brand),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @throws BrandDomainException
     */
    public function update(UpdateBrandRequest $request, string $brandUuid): JsonResponse
    {
        $brandDto = UpdateBrandDTO::fromRequestUuid($request, $brandUuid);

        $brand = $this->updateBrandUseCase->execute($brandDto);

        return response()->json([
            'data' => BrandResource::BrandToArray($brand),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $brandUuid): JsonResponse
    {
        $this->deleteBrandByUuidUseCase->execute($brandUuid);

        return response()->json([], 204);
    }
}

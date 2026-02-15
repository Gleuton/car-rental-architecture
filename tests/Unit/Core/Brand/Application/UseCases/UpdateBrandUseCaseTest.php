<?php

use App\Core\Brand\Application\UseCases\UpdateBrandUseCase;
use App\Core\Brand\Application\DTOs\UpdateBrandDto;
use App\Core\Brand\Domain\Repositories\BrandRepositoryInterface;
use App\Core\Brand\Domain\Entity\Brand;
use App\Http\Requests\Brand\UpdateBrandRequest;

it('deve atualizar uma marca com sucesso', function () {
    $requestMock = Mockery::mock(UpdateBrandRequest::class);
    $requestMock->name = 'Fiat Updated';
    $requestMock->image = 'fiat_updated.png';

    $dto = UpdateBrandDto::fromRequestId($requestMock,1);
    
    $repository = Mockery::mock(BrandRepositoryInterface::class);
    
    $expectedBrand = Brand::restore(1, 'Fiat Updated', 'fiat_updated.png');
    $repository->shouldReceive('update')
        ->with($dto)
        ->once()
        ->andReturn($expectedBrand);

    $useCase = new UpdateBrandUseCase($repository);
    $result = $useCase->execute($dto);

    expect($result)->toBe($expectedBrand);
});

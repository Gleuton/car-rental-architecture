<?php

use App\Core\Brand\Application\UseCases\FindBrandByIdUseCase;
use App\Core\Brand\Application\DTOs\BrandIdDTO;
use App\Core\Brand\Domain\Repositories\BrandRepositoryInterface;
use App\Core\Brand\Domain\Entity\Brand;

it('deve encontrar uma marca pelo ID com sucesso', function () {
    $dto = BrandIdDTO::fromId(1);
    
    $repository = Mockery::mock(BrandRepositoryInterface::class);
    
    $expectedBrand = Brand::createWithId(1, 'Fiat', 'fiat.png');
    $repository->shouldReceive('findById')
        ->with(1)
        ->once()
        ->andReturn($expectedBrand);

    $useCase = new FindBrandByIdUseCase($repository);
    $result = $useCase->execute($dto);

    expect($result)->toBe($expectedBrand);
});

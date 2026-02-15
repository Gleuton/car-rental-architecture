<?php

use App\Core\Brand\Application\UseCases\DeleteBrandUseCase;
use App\Core\Brand\Application\DTOs\BrandIdDTO;
use App\Core\Brand\Domain\Repositories\BrandRepositoryInterface;

it('deve deletar uma marca com sucesso', function () {
    $dto = BrandIdDTO::fromId(1);
    
    $repository = Mockery::mock(BrandRepositoryInterface::class);
    $repository->shouldReceive('delete')
        ->with(1)
        ->once();

    $useCase = new DeleteBrandUseCase($repository);
    $useCase->execute($dto);
    
    expect(true)->toBeTrue();
});

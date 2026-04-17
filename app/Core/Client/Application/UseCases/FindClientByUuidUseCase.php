<?php

declare(strict_types=1);

namespace App\Core\Client\Application\UseCases;

use App\Core\Client\Application\DTOs\ClientUuidDTO;
use App\Core\Client\Domain\Entity\Client;
use App\Core\Client\Domain\Repositories\ClientRepositoryInterface;

readonly class FindClientByUuidUseCase
{
    public function __construct(
        private ClientRepositoryInterface $repository
    ) {}

    public function execute(ClientUuidDTO $dto): Client
    {
        return $this->repository->findByUuid($dto->uuid);
    }
}
